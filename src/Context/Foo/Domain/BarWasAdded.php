<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain;

use App\Shared\Domain\Bus\Event\DomainEvent;

class BarWasAdded extends DomainEvent
{
    public function __construct(
        private string $fooId,
        private string $barId,
        private string $name,
        ?string $occurredOn = null
    ) {
        parent::__construct($occurredOn);
    }

    public static function create(string $fooId, string $barId, string $name): self
    {
        return new self($fooId, $barId, $name);
    }

    public function fooId(): string
    {
        return $this->fooId;
    }

    public function barId(): string
    {
        return $this->barId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
