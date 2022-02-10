<?php

declare(strict_types=1);

namespace App\Tests\Shared\Context\Foo\Domain;

use App\Context\Foo\Domain\ValueObject\FooId;

class FooIdStub
{
    public const DEFAULT_FOO_ID = '21e0b058-62bd-411d-9f42-1184aab69fb6';

    public static function create(string $value): FooId
    {
        return FooId::fromString($value);
    }

    public static function default(): FooId
    {
        return self::create(self::DEFAULT_FOO_ID);
    }
}
