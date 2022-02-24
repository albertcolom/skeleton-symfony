<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain;

use App\Shared\Domain\Bus\Event\DomainEvent;

class FooWasCreated extends DomainEvent
{
    public function __construct(
        private string $fooId,
        private string $name,
        private string $createdAt,
        ?string $occurredOn = null
    ) {
        parent::__construct($occurredOn);
    }

    public static function create(string $fooId, string $name, string $createdAt): self
    {
        return new self($fooId, $name, $createdAt);
    }

    public function fooId(): string
    {
        return $this->fooId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function createdAt(): string
    {
        return $this->createdAt;
    }
}
