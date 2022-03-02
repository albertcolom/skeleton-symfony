<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Repository\Write;

use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\FooCollection;
use App\Context\Foo\Domain\ValueObject\FooId;

interface FooRepository
{
    public function findAll(): FooCollection;

    public function findById(FooId $fooId): ?Foo;

    public function save(Foo $foo): void;

    public function remove(FooId $fooId): void;
}
