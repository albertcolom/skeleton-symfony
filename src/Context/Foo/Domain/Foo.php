<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain;

use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Aggregate\AggregateRoot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Foo extends AggregateRoot
{
    private Collection $bars;

    public function __construct(
        private FooId $id,
        private string $name
    ) {
        $this->bars = new ArrayCollection();
    }

    public static function create(FooId $id, string $name): self
    {
        $foo = new self($id, $name);

        $foo->recordEvent(FooWasCreated::create($foo->id, $foo->name));

        return $foo;
    }

    public function fooId(): FooId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function bars(): Collection
    {
        return $this->bars;
    }
}
