<?php

declare(strict_types=1);

namespace App\Shared\Domain\Write\Aggregate;

use App\Shared\Domain\Write\Event\DomainEvent;

abstract class AggregateRoot
{
    /** @var array<DomainEvent> */
    private array $domainEvents = [];

    final protected function recordEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    final public function domainEventsEmpty(): bool
    {
        return empty($this->domainEvents);
    }

    /** @return array<DomainEvent> */
    final public function pullDomainEvents(): array
    {
        $recordedEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $recordedEvents;
    }
}
