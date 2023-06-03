<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Query\FindAll\FindAllFooQuery;
use App\Shared\Domain\Bus\Query\CacheQueryBus;
use App\Shared\Domain\QueryParams\QueryParams;
use App\Shared\Infrastructure\Request\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetAllFooController
{
    public function __construct(
        private readonly CacheQueryBus $cacheQueryBus,
        private readonly RequestValidator $requestValidator
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->requestValidator->validate($request);

        $queryParams = QueryParams::fromArray($request->query->all());

        $response = $this->cacheQueryBus->ask(new FindAllFooQuery($queryParams->toArray()));

        return new JsonResponse($response->result(), Response::HTTP_OK);
    }
}
