<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Bus\Event\DomainEvent;

abstract class AggregateRoot
{
    private array $domainEvents = [];

    final protected function recordEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    final public function pullDomainEvents(): array
    {
        $recordedEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $recordedEvents;
    }
}
