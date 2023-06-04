<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Write;

use App\Shared\Domain\Write\TypedCollection;

final class FooCollection extends TypedCollection
{
    protected function type(): string
    {
        return Foo::class;
    }
}
