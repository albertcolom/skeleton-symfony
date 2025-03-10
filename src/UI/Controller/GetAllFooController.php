<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Query\FindAll\FindAllFooQuery;
use App\Shared\Application\Bus\Query\CacheQueryBus;
use App\Shared\Domain\Read\QueryParams\QueryParams;
use App\Shared\Infrastructure\Request\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class GetAllFooController
{
    public function __construct(
        private CacheQueryBus $cacheQueryBus,
        private RequestValidator $requestValidator
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
