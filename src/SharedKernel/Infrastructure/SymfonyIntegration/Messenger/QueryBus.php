<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger;

use App\SharedKernel\Application\Bus\QueryBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class QueryBus extends Bus implements QueryBusInterface
{
    public function query(object $query): mixed
    {
        $envelop = $this->bus->dispatch($query);

        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelop->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}