<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Command\Create\CreateFooCommand;
use App\Context\Foo\Application\Command\Update\UpdateFooCommand;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Shared\Application\Bus\Command\CommandBus;
use App\Shared\Infrastructure\Request\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class PutFooController
{
    public function __construct(
        private CommandBus $commandBus,
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
        } catch (FooNotFoundException) {
            $this->commandBus->dispatch(new CreateFooCommand($request->get('fooId'), $content['name']));
            $headers = [
                'Location' => $this->getResourceUrl($request->get('fooId'))
            ];
        }

        return new JsonResponse(null, Response::HTTP_ACCEPTED, $headers);
    }

    private function getResourceUrl(string $fooId): string
    {
        return $this->urlGenerator->generate(
            'get_v1_foo',
            [
                'fooId' => $fooId,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
