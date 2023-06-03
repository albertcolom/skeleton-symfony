<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Middleware;

use App\Shared\Infrastructure\Bus\Serializer\MessengerMessageSerializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

final class MessengerEventLogMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface $eventLogger,
        private readonly MessengerMessageSerializer $messengerMessageSerializer
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);
        $receive = $envelope->last(ReceivedStamp::class);

        if (!$receive) {
            $message = $this->messengerMessageSerializer->encode($envelope);
            $body = json_decode($message['body'], true, 512, JSON_THROW_ON_ERROR);
            $this->eventLogger->info(
                sprintf('An event has been published "%s"', $body['metadata']['name'] ?? ''),
                $body
            );
        }

        return $envelope;
    }
}
