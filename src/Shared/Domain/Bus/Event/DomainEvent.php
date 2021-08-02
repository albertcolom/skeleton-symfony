<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;

abstract class DomainEvent
{
    public function __construct(
        private Uuid $aggregateId,
        private ?Uuid $eventId = null,
        private ?DateTimeImmutable $occurredOn = null
    ) {
        $this->eventId = $eventId ?: Uuid::random();
        $this->occurredOn = $occurredOn ?: new DateTimeImmutable();
    }

    abstract public function toPrimitives(): array;

    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function version(): string;

    public function aggregateId(): Uuid
    {
        return $this->aggregateId;
    }

    public function eventId(): Uuid
    {
        return $this->eventId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
