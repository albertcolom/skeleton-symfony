<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Query\Find\FindFooQuery;
use App\Shared\Domain\Bus\Query\CacheQueryBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Infrastructure\Request\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetFooController
{
    public function __construct(private CacheQueryBus $cacheQueryBus, private RequestValidator $requestValidator)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->requestValidator->validate($request);

        $response = $this->cacheQueryBus->ask(new FindFooQuery($request->get('fooId')));

        return new JsonResponse($response->result(), Response::HTTP_OK);
    }
}
