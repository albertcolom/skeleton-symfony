<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\Find;

use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Query\QueryHandler;

class FindFooQueryHandler implements QueryHandler
{
    public function __construct(private FooViewRepository $fooViewRepository)
    {
    }

    public function __invoke(FindFooQuery $query): FindFooQueryResponse
    {
        $id = FooId::fromString($query->id());
        $foo = $this->fooViewRepository->findById($id);

        if (null === $foo) {
            throw FooNotFoundException::fromFooId($query->id());
        }

        return FindFooQueryResponse::fromFoo($foo);
    }
}
