<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Bar\BarCollection;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Aggregate\AggregateRoot;
use Doctrine\Common\Collections\Collection;

class Foo extends AggregateRoot
{
    private Collection $bars;

    public function __construct(
        private FooId $id,
        private string $name
    ) {
        $this->bars = BarCollection::createEmpty();
    }

    public static function create(FooId $id, string $name): self
    {
        $foo = new self($id, $name);
        $foo->recordEvent(FooWasCreated::create($foo->id->value(), $foo->name));

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

    public function addBar(Bar $bar): void
    {
        $this->bars->add($bar);

        $this->recordEvent(BarWasAdded::create($this->fooId()->value(), $bar->barId()->value(), $bar->name()));
    }

    public function update(string $name): self
    {
        $this->name = $name;

        $this->recordEvent(FooWasUpdated::create($this->fooId()->value(), $this->name()));

        return $this;
    }

    public function remove(): void
    {
        $this->recordEvent(FooWasRemoved::create($this->fooId()->value()));
    }

    public function equals(self $other): bool
    {
        return $this->fooId()->equals($other->fooId())
            && $this->name() === $other->name();
    }
}
