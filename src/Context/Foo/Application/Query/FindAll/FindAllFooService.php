<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Domain\Read\Repository\FooViewRepository;
use App\Context\Foo\Domain\Read\View\FooViewCollection;
use App\Shared\Domain\Read\QueryParams\QueryParams;

class FindAllFooService
{
    public function __construct(private readonly FooViewRepository $fooViewRepository)
    {
    }

    public function execute(?QueryParams $queryParams = null): FooViewCollection
    {
        return $this->fooViewRepository->findAll($queryParams ?? new QueryParams());
    }
}
