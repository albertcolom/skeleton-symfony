<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindById;

use App\Context\Foo\Domain\Repository\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Query\QueryHandler;

class FindByIdQueryHandler implements QueryHandler
{
    public function __construct(private FooRepository $fooRepository)
    {
    }

    public function __invoke(FindByIdQuery $query): FindByIdQueryResponse
    {
        $id = FooId::fromString($query->id());
        $foo = $this->fooRepository->findById($id);

        $array = $foo->bars()->toArray();

        return FindByIdQueryResponse::fromFoo($foo);
    }
}
