<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Middleware;

use App\Shared\Infrastructure\Bus\Serializer\MessengerMessageSerializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;

class MessengerEventLogMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $eventLogger,
        private MessengerMessageSerializer $messengerMessageSerializer
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        $message = $this->messengerMessageSerializer->encode($envelope);

        $receive = $envelope->last(ReceivedStamp::class);
        $sent = $envelope->last(SentStamp::class);

        $body = json_decode($message['body'], true, 512, JSON_THROW_ON_ERROR);

        if (!$receive){
            $this->eventLogger->info(
                sprintf('An event has been published "%s"', $body['metadata']['name'] ?? ''),
                $body
            );
        }

        return $envelope;
    }
}
