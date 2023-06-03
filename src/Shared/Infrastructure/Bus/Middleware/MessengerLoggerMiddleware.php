<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Middleware;

use App\Shared\Infrastructure\Bus\Serializer\MessengerMessageSerializer;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;

final class MessengerLoggerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface $messageLogger,
        private readonly MessengerMessageSerializer $messengerMessageSerializer
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $start = new DateTimeImmutable();

        $message = $this->messengerMessageSerializer->encode($envelope);
        $body = json_decode($message['body'], true, 512, JSON_THROW_ON_ERROR);
        $name = $body['metadata']['name'] ?? '';

        try {
            $envelope = $stack->next()->handle($envelope, $stack);

            $this->messageLogger->info(
                sprintf('A message has been handled "%s"', $name),
                array_merge(
                    $body,
                    [
                        'execution_time' => [
                            'start' => $start->format('y:m:d h:i:s.u'),
                            'finish' => (new DateTimeImmutable())->format('y:m:d h:i:s.u'),
                        ]
                    ]
                )
            );
        } catch (Throwable $exception) {
            $this->messageLogger->error(
                sprintf('An exception occurred while handling the message "%s"', $name),
                array_merge(
                    $body,
                    [
                        'exception' => [
                            'code' => $exception->getCode(),
                            'message' => $exception->getMessage(),
                        ]
                    ]
                )
            );
            throw $exception;
        }

        return $envelope;
    }
}
