<?php

declare(strict_types=1);

namespace App\Tests\Shared\Stubs\Foo\Read\Bar;

use App\Context\Foo\Domain\Read\View\BarView\BarView;
use App\Tests\Shared\Stubs\Shared\UuidMother;
use App\Tests\Shared\Stubs\Shared\WordMother;

class BarViewMother
{
    public static function create(string $id, string $name): BarView
    {
        return new BarView($id, $name);
    }

    public static function random(): BarView
    {
        return self::create(UuidMother::random(), WordMother::random());
    }
}
