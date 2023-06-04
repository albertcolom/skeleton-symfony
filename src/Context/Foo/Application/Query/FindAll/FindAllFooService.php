<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Shared\Domain\Read\QueryParams\QueryParams;

class FindAllFooService
{
    public function __construct(private readonly FooViewRepository $fooViewRepository)
    {
    }

    public function execute(?QueryParams $queryParams = null): FindAllFooQueryResponse
    {
        return FindAllFooQueryResponse::fromFooCollection(
            $this->fooViewRepository->findAll($queryParams ?? new QueryParams())
        );
    }
}
