<?php

declare(strict_types=1);

namespace App\Tests\Shared\Context\Foo\Domain;

use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\ValueObject\FooId;
use DateTimeImmutable;

class FooStub
{
    public const DEFAULT_FOO_NAME = 'Default Foo name';
    public const DEFAULT_CREATED_AT = '2011-11-11 11:11:11';

    public static function create(FooId $id, string $name, DateTimeImmutable $createdAt): Foo
    {
        return new Foo($id, $name, $createdAt);
    }

    public static function default(): Foo
    {
        return self::create(
            FooIdStub::default(),
            self::DEFAULT_FOO_NAME,
             DateTimeImmutable::createFromFormat('Y-m-d H:i:s', self::DEFAULT_CREATED_AT)
        );
    }
}
