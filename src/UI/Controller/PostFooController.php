<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Command\Create\CreateFooCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Infrastructure\Request\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PostFooController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly RequestValidator $requestValidator,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->requestValidator->validate($request);
        $content = $request->toArray();

        $this->commandBus->dispatch(new CreateFooCommand($content['id'], $content['name']));

        return new JsonResponse(
            null,
            Response::HTTP_ACCEPTED,
            [
                'Location' => $this->getResourceUrl($content['id'])
            ]
        );
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
