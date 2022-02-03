<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\Find;

use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\ValueObject\FooId;

class FindFooService
{
    public function __construct(private FooViewRepository $fooViewRepository)
    {
    }

    public function execute(FooId $fooId): FindFooQueryResponse
    {
        $foo = $this->fooViewRepository->findById($fooId);

        if (null === $foo) {
            throw FooNotFoundException::fromFooId($fooId->value());
        }

        return FindFooQueryResponse::fromFoo($foo);
    }
}
