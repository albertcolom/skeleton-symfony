<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Command\Create\CreateFooCommand;
use App\Context\Foo\Application\Query\Find\FindFooQuery;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Infrastructure\Request\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostFooController
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus,
        private RequestValidator $requestValidator
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->requestValidator->validate($request);
        $content = $request->toArray();

        $this->commandBus->dispatch(new CreateFooCommand($content['id'], $content['name']));

        $response = $this->queryBus->ask(new FindFooQuery($content['id']));

        return new JsonResponse($response->result(), Response::HTTP_CREATED);
    }
}
