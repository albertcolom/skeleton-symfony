<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

class FooWasRemoved extends DomainEvent
{
    public function __construct(
        private string $fooId,
        ?string $occurredOn = null
    ) {
        parent::__construct($occurredOn);
    }

    public static function create(string $fooId): self
    {
        return new self($fooId);
    }

    public function fooId(): string
    {
        return $this->fooId;
    }
}
