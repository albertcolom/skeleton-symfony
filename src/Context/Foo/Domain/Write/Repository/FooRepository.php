<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Write\Repository;

use App\Context\Foo\Domain\Write\Foo;
use App\Context\Foo\Domain\Write\FooCollection;
use App\Context\Foo\Domain\Write\ValueObject\FooId;

interface FooRepository
{
    public function findAll(): FooCollection;

    public function findById(FooId $fooId): ?Foo;

    public function save(Foo $foo): void;

    public function remove(FooId $fooId): void;
}
