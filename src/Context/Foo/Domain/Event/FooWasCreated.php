<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class FooWasCreated extends DomainEvent
{
    public function __construct(
        public readonly string $fooId,
        public readonly string $name,
        public readonly string $createdAt,
        ?string $occurredOn = null
    ) {
        parent::__construct($occurredOn);
    }

    public static function create(string $fooId, string $name, string $createdAt): self
    {
        return new self($fooId, $name, $createdAt);
    }
}
