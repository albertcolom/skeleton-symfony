<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain;

use App\Shared\Domain\TypedCollection;

final class FooCollection extends TypedCollection
{
    protected function type(): string
    {
        return Foo::class;
    }
}
