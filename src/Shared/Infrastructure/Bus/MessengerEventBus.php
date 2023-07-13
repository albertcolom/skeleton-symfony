<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\Event\EventBus;
use App\Shared\Domain\Write\Event\DomainEvent;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final readonly class MessengerEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function publish(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $currentEvent) {
            $this->messageBus->dispatch(
                (new Envelope($currentEvent))->with(new DispatchAfterCurrentBusStamp())
            );
        }
    }
}
