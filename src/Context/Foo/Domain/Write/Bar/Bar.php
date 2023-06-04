<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Write\Bar;

use App\Context\Foo\Domain\Write\Bar\ValueObject\BarId;
use App\Context\Foo\Domain\Write\Foo;

final class Bar
{
    public function __construct(
        public readonly Foo $foo,
        public readonly BarId $id,
        public readonly string $name
    ) {
    }

    public function equals(self $other): bool
    {
        return $this->foo->equals($other->foo) && $this->id->equals($other->id) && $this->name === $other->name;
    }
}
