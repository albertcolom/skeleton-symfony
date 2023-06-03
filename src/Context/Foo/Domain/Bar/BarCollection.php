<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Bar;

use App\Shared\Domain\TypedCollection;

final class BarCollection extends TypedCollection
{
    protected function type(): string
    {
        return Bar::class;
    }
}
