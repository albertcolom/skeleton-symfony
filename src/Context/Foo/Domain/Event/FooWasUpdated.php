<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

class FooWasUpdated extends DomainEvent
{
    public function __construct(
        private string $fooId,
        private string $name,
        ?string $occurredOn = null
    ) {
        parent::__construct($occurredOn);
    }

    public static function create(string $fooId, string $name): self
    {
        return new self($fooId, $name);
    }

    public function fooId(): string
    {
        return $this->fooId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
