<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Bar\BarCollection;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Aggregate\AggregateRoot;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;

class Foo extends AggregateRoot
{
    private Collection $bars;

    public function __construct(
        private FooId $id,
        private string $name,
        private DateTimeImmutable $createdAt
    ) {
        $this->bars = BarCollection::createEmpty();
        $this->recordEvent(
            FooWasCreated::create($id->value(), $name, $createdAt->format('Y-m-d H:i:s'))
        );
    }

    public static function create(FooId $id, string $name, DateTimeImmutable $createdAt): self
    {
        return new self($id, $name, $createdAt);
    }

    public function fooId(): FooId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
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
            && $this->name() === $other->name()
            && $this->createdAt()->format(DateTime::ATOM) === $other->createdAt()->format(DateTime::ATOM);
    }
}
