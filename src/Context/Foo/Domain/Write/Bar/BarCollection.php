<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Write\Bar;

use App\Shared\Domain\Write\TypedCollection;

/** @extends TypedCollection<array-key, Bar> */
final class BarCollection extends TypedCollection
{
    protected function type(): string
    {
        return Bar::class;
    }
}
