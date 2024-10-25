<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Write;

use App\Context\Foo\Domain\Write\Bar\Bar;
use App\Context\Foo\Domain\Write\Bar\BarCollection;
use App\Context\Foo\Domain\Write\Event\BarWasAdded;
use App\Context\Foo\Domain\Write\Event\FooWasCreated;
use App\Context\Foo\Domain\Write\Event\FooWasRemoved;
use App\Context\Foo\Domain\Write\Event\FooWasUpdated;
use App\Context\Foo\Domain\Write\ValueObject\FooId;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

final class Foo extends AggregateRoot
{
    /** @var Collection<int, Bar> */
    private Collection $bars;

    public function __construct(
        public readonly FooId $id,
        private string $name,
        private readonly DateTimeImmutable $createdAt
    ) {
        $this->bars = BarCollection::createEmpty();
        $this->recordEvent(
            new FooWasCreated($id->value, $name, $createdAt->format('Y-m-d H:i:s'))
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, Bar> */
    public function bars(): Collection
    {
        return $this->bars;
    }

    public function addBar(Bar $bar): void
    {
        $this->bars->add($bar);

        $this->recordEvent(new BarWasAdded($this->id->value, $bar->id->value, $bar->name));
    }

    public function update(string $name): self
    {
        $this->name = $name;

        $this->recordEvent(new FooWasUpdated($this->id->value, $this->name()));

        return $this;
    }

    public function remove(): void
    {
        $this->recordEvent(new FooWasRemoved($this->id->value));
    }

    public function equals(self $other): bool
    {
        return $this->id->equals($other->id)
            && $this->name() === $other->name()
            && $this->createdAt()->format(DateTimeInterface::ATOM) === $other->createdAt()
                ->format(DateTimeInterface::ATOM);
    }
}
