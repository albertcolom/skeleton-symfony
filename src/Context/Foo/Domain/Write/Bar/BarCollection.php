<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Write\Bar;

use App\Shared\Domain\Write\TypedCollection;

final class BarCollection extends TypedCollection
{
    protected function type(): string
    {
        return Bar::class;
    }
}
