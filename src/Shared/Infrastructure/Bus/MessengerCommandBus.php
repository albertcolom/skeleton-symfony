<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $exception) {
            throw current($exception->getNestedExceptions());
        }
    }
}
