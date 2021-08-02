<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain;

use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Event\EventId;
use DateTimeImmutable;

class FooWasCreated extends DomainEvent
{
    public function __construct(
        private FooId $fooId,
        private string $name,
        ?EventId $eventId = null,
        ?DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($fooId, $eventId, $occurredOn);
    }

    public static function create(FooId $fooId, string $name): self
    {
        return new self($fooId, $name);
    }

    public function fooId(): FooId
    {
        return $this->fooId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self {
        return new self(
            FooId::fromString($aggregateId),
            $body['name'],
            EventId::fromString($eventId),
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $occurredOn)
        );
    }

    public function toPrimitives(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public static function version(): string
    {
        return '1.0';
    }
}
