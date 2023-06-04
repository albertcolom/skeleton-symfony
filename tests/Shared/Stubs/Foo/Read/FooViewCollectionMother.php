<?php

declare(strict_types=1);

namespace App\Tests\Shared\Stubs\Foo\Read;

use App\Context\Foo\Domain\Read\View\FooViewCollection;

class FooViewCollectionMother
{
    public static function create(array $elements = []): FooViewCollection
    {
        return new FooViewCollection($elements);
    }

    public static function random(): FooViewCollection
    {
        return self::create(
            [
                FooViewMother::random(),
                FooViewMother::random(),
            ]
        );
    }
}
