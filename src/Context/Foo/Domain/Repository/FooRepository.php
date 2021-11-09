<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Repository;

use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\ValueObject\FooId;

interface FooRepository
{
    public function findById(FooId $fooId): ?Foo;

    public function findAll(): array;

    public function save(Foo $foo): void;

    public function remove(FooId $fooId): void;
}
