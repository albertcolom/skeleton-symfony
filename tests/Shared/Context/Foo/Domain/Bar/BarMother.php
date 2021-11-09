<?php

declare(strict_types=1);

namespace App\Tests\Shared\Context\Foo\Domain\Bar;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Bar\ValueObject\BarId;
use App\Context\Foo\Domain\Foo;
use App\Tests\Shared\Context\Foo\Domain\FooMother;

class BarMother
{
    public const DEFAULT_BAR_NAME = 'Default bar name';

    public static function create(Foo $foo, BarId $barId, string $name): Bar
    {
        return Bar::create($foo, $barId, $name);
    }

    public static function default(): Bar
    {
        return self::create(FooMother::default(), BarIdMother::default(), self::DEFAULT_BAR_NAME);
    }
}
