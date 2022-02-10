<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Repository\Read;

use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\FooCollection;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\QueryParams\QueryParams;

interface FooViewRepository
{
    public function findById(FooId $fooId): ?Foo;

    public function findAll(QueryParams $queryParams): FooCollection;
}
