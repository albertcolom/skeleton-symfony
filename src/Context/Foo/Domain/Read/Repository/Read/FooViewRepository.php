<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Read\Repository\Read;

use App\Context\Foo\Domain\Read\View\FooView;
use App\Context\Foo\Domain\Read\View\FooViewCollection;
use App\Context\Foo\Domain\Write\ValueObject\FooId;
use App\Shared\Domain\Read\QueryParams\QueryParams;

interface FooViewRepository
{
    public function findById(FooId $fooId): FooView;

    public function findAll(QueryParams $queryParams): FooViewCollection;
}
