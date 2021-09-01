<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger\Middleware;

use Assert\Assertion;
use Closure;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Types;
use ProxyManager\Proxy\ValueHolderInterface;
use Ramsey\Uuid\Uuid;
use ReflectionFunction;
use ReflectionObject;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Throwable;

final class SynchronizeWithAsynchronousCommandsMiddleware implements MiddlewareInterface
{
    private const TABLE_NAME = 'messenger_results';
    private const POLLING_INTERVAL = 5000;
    private const MAX_FLATTENING_LEVEL = 4;

    private ?string $expectedExceptionClass = null;

    private ?string $expectedExceptionMessage = null;

    public function __construct(private SerializerInterface $serializer, private ?Connection $dbalConnection = null)
    {
    }

    public function createDataBaseTable(): void
    {
        Assertion::notNull($this->dbalConnection);

        $schemaManager = $this->dbalConnection->getSchemaManager();

        if (true === $this->tableExists()) {
            return;
        }

        $table = new Table(self::TABLE_NAME);
        $table->addColumn('id', 'uuid');
        $table->addColumn('finished_at', 'datetime_immutable_with_microseconds', [
            'columnDefinition' => 'DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6)',
        ]);
        $table->addColumn('headers', 'text');
        $table->addColumn('body', 'text');
        $table->addColumn('properties', 'text');
        $table->addColumn('success', 'boolean');
        $table->addColumn('exception', 'text', ['notnull' => false]);

        $table->setPrimaryKey(['id']);

        $schemaManager->createTable($table);
    }

    public function reset(): void
    {
        Assertion::notNull($this->dbalConnection);

        $this->dbalConnection->executeStatement('DELETE FROM ' . self::TABLE_NAME);
        $this->expectedExceptionClass = null;
        $this->expectedExceptionMessage = null;
    }

    public function expectException(string $class, ?string $message = null): void
    {
        $this->expectedExceptionClass = $class;
        $this->expectedExceptionMessage = $message;
    }

    /** @return AsynchronousCommandResult[] */
    public function waitForResults(int $count): array
    {
        $results = $this->getResults();
        while (count($results) < $count) {
            usleep(self::POLLING_INTERVAL);

            $results = $this->getResults();
            array_walk($results, [$this, 'throwAsynchronousExceptionIfExist']);
        }
        array_walk($results, [$this, 'throwAsynchronousExceptionIfExist']);

        if (count($results) > $count) {
            throw new RuntimeException(sprintf('Expected %d results but found %d', $count, count($results)));
        }

        return $results;
    }

    /** @return AsynchronousCommandResult[] */
    public function getResults(): array
    {
        Assertion::notNull($this->dbalConnection);

        $stmt = $this->dbalConnection->executeQuery('SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY finished_at ASC');
        $rows = $stmt->fetchAllAssociative();

        return array_map(function (array $row): AsynchronousCommandResult {
            return new AsynchronousCommandResult(
                $this->serializer->decode([
                    'headers' => json_decode($row['headers'], true),
                    'body' => $row['body'],
                    'properties' => json_decode($row['properties'], true),
                ]),
                (bool)$row['success'],
                unserialize($row['exception'])
            );
        }, $rows);
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(ReceivedStamp::class)) {
            return $stack->next()->handle($envelope, $stack);
        }

        try {
            $envelope = $stack->next()->handle($envelope, $stack);

            $this->saveRow($envelope, true, null);
        } catch (Throwable $throwable) {
            $this->saveRow($envelope, false, $throwable);

            throw $throwable;
        }

        return $envelope;
    }

    private function saveRow(Envelope $command, bool $success, ?Throwable $throwable): void
    {
        Assertion::notNull($this->dbalConnection);

        $encodedMessage = $this->serializer->encode($command);
        if (null !== $throwable) {
            $this->flattenValue($throwable);
        }

        $row = [
            'id' => Uuid::uuid4(),
            'headers' => json_encode($encodedMessage['headers'] ?? []),
            'body' => $encodedMessage['body'],
            'properties' => json_encode($encodedMessage['properties'] ?? []),
            'success' => $success,
            'exception' => serialize($throwable),
        ];

        $this->dbalConnection->insert(self::TABLE_NAME, $row, [
            'id' => 'uuid',
            'body' => Types::TEXT,
            'headers' => Types::TEXT,
            'properties' => Types::TEXT,
            'success' => Types::BOOLEAN,
            'exception' => Types::TEXT,
        ]);
    }

    private function flattenValue(mixed &$value, int $level = 0): void
    {
        if ($value instanceof Closure) {
            $closureReflection = new ReflectionFunction($value);
            $value = sprintf(
                '(Closure at %s:%s)',
                $closureReflection->getFileName(),
                $closureReflection->getStartLine()
            );
        } elseif ($value instanceof ValueHolderInterface) {
            $value = $value->getWrappedValueHolderValue();
            if (null !== $value) {
                $this->flattenValue($value, $level);
            }
        } elseif (is_object($value)) {
            $value = $this->flattenObject($value, $level);
        } elseif (is_resource($value)) {
            $value = sprintf('resource(%s)', get_resource_type($value));
        } elseif (is_array($value)) {
            array_walk_recursive($value, fn(&$value) => $this->flattenValue($value, $level + 1));
        }
    }

    private function flattenObject(object $object, int $level): mixed
    {
        if ($level > self::MAX_FLATTENING_LEVEL) {
            return sprintf('[truncated object of class "%s"]', get_class($object));
        }

        $objectReflection = new ReflectionObject($object);
        $properties = $objectReflection->getProperties();
        while ($objectReflection = $objectReflection->getParentClass()) {
            $properties = array_merge($properties, $objectReflection->getProperties());
        }
        $properties = array_unique($properties);

        if (true === $object instanceof Envelope) {
            return $object;
        }

        $result = ['__CLASS__' => get_class($object)];

        if (true === $object instanceof Throwable) {
            $result = $object;
        }

        array_walk($properties, function (ReflectionProperty $property) use ($object, &$result, $level): void {
            $property->setAccessible(true);

            $propertyValue = $property->getValue($object);
            $this->flattenValue($propertyValue, $level + 1);
            if (true === is_array($result)) {
                $result[$property->name] = $propertyValue;
            } else {
                $property->setValue($object, $propertyValue);
            }
        });

        return $result;
    }

    private function tableExists(): bool
    {
        Assertion::notNull($this->dbalConnection);
        $tables = array_map(
            fn (array $data): array => reset($data),
            $this->dbalConnection->fetchAllNumeric('SHOW TABLES'),
        );

        return in_array(self::TABLE_NAME, $tables[0], true);
    }

    private function throwAsynchronousExceptionIfExist(AsynchronousCommandResult $result): void
    {
        $throwable = $result->getThrowable();
        if (null === $throwable || true === $result->isSuccess() || true === $this->isExpected($throwable)) {
            return;
        }

        if (true === $throwable instanceof HandlerFailedException && 1 === count($throwable->getNestedExceptions())) {
            $throwableClass = get_class($throwable->getNestedExceptions()[0]);
        } else {
            $throwableClass = get_class($throwable);
        }
        $messageProperty = (new ReflectionObject($throwable))->getProperty('message');
        $messageProperty->setAccessible(true);
        $messageProperty->setValue($throwable, sprintf(
            'An exception occurred while asynchronously handling message %s: [%s] %s',
            get_class($result->getCommand()->getMessage()),
            $throwableClass,
            $messageProperty->getValue($throwable)
        ));
        $messageProperty->setAccessible(false);

        throw $throwable;
    }

    private function isExpected(Throwable $throwable): bool
    {
        if (null === $this->expectedExceptionClass) {
            return false;
        }

        if ($throwable instanceof HandlerFailedException) {
            return $this->hasNoUnexpectedNestedExceptions($throwable);
        }

        if (false === $throwable instanceof $this->expectedExceptionClass) {
            return false;
        }

        if (null === $this->expectedExceptionMessage) {
            return true;
        }

        return $throwable->getMessage() === $this->expectedExceptionMessage;
    }

    private function hasNoUnexpectedNestedExceptions(HandlerFailedException $throwable): bool
    {
        return array_reduce(
            $throwable->getNestedExceptions(),
            function (bool $noUnexpectedExceptions, Throwable $nestedException): bool {
                if (false === $noUnexpectedExceptions) {
                    return false;
                }

                return $this->isExpected($nestedException);
            },
            true
        );
    }
}
