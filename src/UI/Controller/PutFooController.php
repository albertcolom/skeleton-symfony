<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Command\Create\CreateFooCommand;
use App\Context\Foo\Application\Command\Update\UpdateFooCommand;
use App\Context\Foo\Application\Query\Find\FindFooQuery;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Infrastructure\Request\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PutFooController
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus,
        private RequestValidator $requestValidator,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->requestValidator->validate($request);
        $content = $request->toArray();
        $headers = [];

        try {
            $this->commandBus->dispatch(new UpdateFooCommand($request->get('fooId'), $content['name']));
            $httpStatus =  Response::HTTP_OK;
        } catch (FooNotFoundException) {
            $this->commandBus->dispatch(new CreateFooCommand($request->get('fooId'), $content['name']));
            $httpStatus = Response::HTTP_CREATED;
            $headers = [
                'Location' => $this->getResourceUrl($request->get('fooId'))
            ];
        }

        $response = $this->queryBus->ask(new FindFooQuery($request->get('fooId')));

        return new JsonResponse($response->result(), $httpStatus, $headers);
    }

    private function getResourceUrl(string $fooId): string
    {
        return $this->urlGenerator->generate(
            'get_foo',
            [
                'fooId' => $fooId,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
