<?php

declare(strict_types=1);

namespace App\Tests\Shared\Context\Foo\Domain;

use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\ValueObject\FooId;

class FooStub
{
    public const DEFAULT_FOO_NAME = 'Default Foo name';

    public static function create(FooId $id, string $name): Foo
    {
        return Foo::create($id, $name);
    }

    public static function default(): Foo
    {
        return self::create(FooIdStub::default(), self::DEFAULT_FOO_NAME);
    }
}
