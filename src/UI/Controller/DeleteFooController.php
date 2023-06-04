<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Command\Delete\DeleteFooCommand;
use App\Shared\Application\Bus\Command\CommandBus;
use App\Shared\Infrastructure\Request\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DeleteFooController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly RequestValidator $requestValidator
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->requestValidator->validate($request);

        $this->commandBus->dispatch(new DeleteFooCommand($request->get('fooId')));

        return new JsonResponse(null, Response::HTTP_ACCEPTED);
    }
}
