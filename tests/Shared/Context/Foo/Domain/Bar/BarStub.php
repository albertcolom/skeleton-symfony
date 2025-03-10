<?php

declare(strict_types=1);

namespace App\Tests\Shared\Context\Foo\Domain\Bar;

use App\Context\Foo\Domain\Write\Bar\Bar;
use App\Context\Foo\Domain\Write\Bar\ValueObject\BarId;
use App\Context\Foo\Domain\Write\Foo;
use App\Tests\Shared\Context\Foo\Domain\FooStub;

class BarStub
{
    public const DEFAULT_BAR_NAME = 'Default bar name';

    public static function create(Foo $foo, BarId $barId, string $name): Bar
    {
        return new Bar($foo, $barId, $name);
    }

    public static function default(): Bar
    {
        return self::create(FooStub::default(), BarIdStub::default(), self::DEFAULT_BAR_NAME);
    }
}
