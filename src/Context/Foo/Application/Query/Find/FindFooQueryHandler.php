<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\Find;

use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Query\QueryHandler;

final class FindFooQueryHandler implements QueryHandler
{
    public function __construct(private readonly FindFooService $findFooQueryService)
    {
    }

    public function __invoke(FindFooQuery $query): FindFooQueryResponse
    {
        return $this->findFooQueryService->execute(FooId::fromString($query->id));
    }
}
