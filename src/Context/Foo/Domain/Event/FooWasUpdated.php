<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class FooWasUpdated extends DomainEvent
{
    public function __construct(
        public readonly string $fooId,
        public readonly string $name,
        ?string $occurredOn = null
    ) {
        parent::__construct($occurredOn);
    }

    public static function create(string $fooId, string $name): self
    {
        return new self($fooId, $name);
    }
}
