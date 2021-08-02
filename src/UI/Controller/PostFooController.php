<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Context\Foo\Application\Command\Create\CreateFooCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class PostFooController
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $content = $request->toArray();
        Assert::keyExists($content, 'name');

        $this->commandBus->dispatch(new CreateFooCommand($content['name']));

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
