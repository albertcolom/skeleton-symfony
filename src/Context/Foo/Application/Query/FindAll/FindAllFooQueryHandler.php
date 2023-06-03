<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\QueryParams\QueryParams;

final class FindAllFooQueryHandler implements QueryHandler
{
    public function __construct(private readonly FindAllFooService $findAllFooService)
    {
    }

    public function __invoke(FindAllFooQuery $query): FindAllFooQueryResponse
    {
        return $this->findAllFooService->execute(QueryParams::fromArray($query->params));
    }
}
