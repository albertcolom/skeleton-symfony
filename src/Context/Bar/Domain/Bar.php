<?php

declare(strict_types=1);

namespace App\Context\Bar\Domain;

use App\Context\Bar\Domain\ValueObject\BarId;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Bar extends AggregateRoot
{
    public function __construct(
        private BarId $id,
        private FooId $fooId,
        private string $name
    ) {
    }

    public function barId(): BarId
    {
        return $this->id;
    }

    public function fooId(): FooId
    {
        return $this->fooId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
