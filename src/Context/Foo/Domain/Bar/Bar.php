<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Bar;

use App\Context\Foo\Domain\Bar\ValueObject\BarId;
use App\Context\Foo\Domain\Foo;

class Bar
{
    public function __construct(
        private Foo $foo,
        private BarId $id,
        private string $name
    ) {
    }

    public static function create(Foo $foo, BarId $barId, string $name): self
    {
        return new self($foo, $barId, $name);
    }

    public function barId(): BarId
    {
        return $this->id;
    }

    public function foo(): Foo
    {
        return $this->foo;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function equals(self $other): bool
    {
        return $this->foo()->equals($other->foo)
            && $this->barId()->equals($other->barId())
            && $this->name() === $other->name();
    }
}
