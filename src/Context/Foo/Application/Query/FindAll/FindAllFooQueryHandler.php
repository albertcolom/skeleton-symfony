<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Shared\Application\Bus\Query\QueryHandler;
use App\Shared\Domain\Read\QueryParams\QueryParams;

final class FindAllFooQueryHandler implements QueryHandler
{
    public function __construct(private readonly FindAllFooService $findAllFooService)
    {
    }

    public function __invoke(FindAllFooQuery $query): FindAllFooQueryResponse
    {
        return FindAllFooQueryResponse::fromFooViewCollection(
            $this->findAllFooService->execute(QueryParams::fromArray($query->params))
        );
    }
}
