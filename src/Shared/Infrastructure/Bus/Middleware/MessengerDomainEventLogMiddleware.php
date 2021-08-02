<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Middleware;

use App\Shared\Infrastructure\Bus\Serializer\MessengerDomainEventJsonSerializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ConsumedByWorkerStamp;

class MessengerDomainEventLogMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $eventLogger,
        private MessengerDomainEventJsonSerializer $messengerDomainEventJsonSerializer
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (!$envelope->last(ConsumedByWorkerStamp::class)) {
            $domainEventMessage = $this->messengerDomainEventJsonSerializer->encode($envelope);
            $this->eventLogger->info($domainEventMessage['body']);
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
