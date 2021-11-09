<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Middleware;

use App\Shared\Infrastructure\Bus\MessengerIdStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class MessengerIdStampMiddleware implements MiddlewareInterface
{

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(MessengerIdStamp::class)) {
            $envelope = $envelope->with(MessengerIdStamp::create());
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
