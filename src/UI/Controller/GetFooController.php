<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Query\FindAll\FindAllQuery;
use App\Context\Foo\Application\Query\FindAll\FindAllQueryResponse;
use App\Context\Foo\Application\Query\FindById\FindByIdQuery;
use App\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetFooController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->get('fooId');

        /** @var FindAllQueryResponse $response */
        $response = $this->queryBus->ask(new FindByIdQuery($id));

        return new JsonResponse($response->result(), Response::HTTP_OK);
    }
}
