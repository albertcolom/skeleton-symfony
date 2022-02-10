<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Shared\Domain\QueryParams\QueryParams;

class FindAllFooService
{
    public function __construct(private FooViewRepository $fooViewRepository)
    {
    }

    public function execute(?QueryParams $queryParams = null): FindAllFooQueryResponse
    {
        return FindAllFooQueryResponse::fromFooCollection(
            $this->fooViewRepository->findAll($queryParams ?? QueryParams::create())
        );
    }
}
