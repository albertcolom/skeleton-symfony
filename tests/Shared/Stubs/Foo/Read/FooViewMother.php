<?php

declare(strict_types=1);

namespace App\Tests\Shared\Stubs\Foo\Read;

use App\Context\Foo\Domain\Read\View\BarView\BarViewCollection;
use App\Context\Foo\Domain\Read\View\FooView;
use App\Tests\Shared\Stubs\Foo\Read\Bar\BarViewCollectionMother;
use App\Tests\Shared\Stubs\Shared\DateTimeMother;
use App\Tests\Shared\Stubs\Shared\UuidMother;
use App\Tests\Shared\Stubs\Shared\WordMother;

class FooViewMother
{
    public static function create(string $id, string $name, BarViewCollection $barsView, string $createdAt): FooView
    {
        return new FooView($id, $name, $barsView, $createdAt);
    }

    public static function random(): FooView
    {
        return self::create(
            UuidMother::random(),
            WordMother::random(),
            BarViewCollectionMother::random(),
            DateTimeMother::randomWithDefaultFormat(),
        );
    }

    public static function randomWithoutBars(): FooView
    {
        return self::create(
            UuidMother::random(),
            WordMother::random(),
            BarViewCollectionMother::create(),
            DateTimeMother::randomWithDefaultFormat(),
        );
    }
}
