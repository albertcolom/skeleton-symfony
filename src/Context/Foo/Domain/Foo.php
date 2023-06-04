<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Bar\BarCollection;
use App\Context\Foo\Domain\Event\BarWasAdded;
use App\Context\Foo\Domain\Event\FooWasCreated;
use App\Context\Foo\Domain\Event\FooWasRemoved;
use App\Context\Foo\Domain\Event\FooWasUpdated;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;

final class Foo extends AggregateRoot
{
    private Collection $bars;

    public function __construct(
        public readonly FooId $id,
        private string $name,
        private DateTimeImmutable $createdAt
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
            && $this->createdAt()->format(DateTime::ATOM) === $other->createdAt()->format(DateTime::ATOM);
    }
}
