<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

abstract class Bus
{
    public function __construct(protected MessageBusInterface $bus)
    {
    }

    protected function addTimestamp(object $object): Envelope
    {
        return (new Envelope($object))->with(
            new SerializerStamp([
                DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
                DateTimeNormalizer::TIMEZONE_KEY => date_default_timezone_get(),
            ])
        );
    }
}
