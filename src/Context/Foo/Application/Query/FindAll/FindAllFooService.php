<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Domain\Repository\Read\FooViewRepository;

class FindAllFooService
{
    public function __construct(private FooViewRepository $fooViewRepository)
    {
    }

    public function execute(): FindAllFooQueryResponse
    {
        $foo = $this->fooViewRepository->findAll();

        return FindAllFooQueryResponse::fromFooCollection($foo);
    }
}
