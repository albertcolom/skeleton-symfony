<?php

declare(strict_types=1);

namespace App\Tests\Shared\Stubs\Foo\Read\Bar;

use App\Context\Foo\Domain\Read\View\BarView\BarViewCollection;

class BarViewCollectionMother
{
    public static function create(array $elements = []): BarViewCollection
    {
        return new BarViewCollection($elements);
    }

    public static function random(): BarViewCollection
    {
        return self::create(
            [
                BarViewMother::random(),
                BarViewMother::random(),
            ]
        );
    }
}
