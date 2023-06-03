<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Create;

use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Command\CommandHandler;

final class CreateFooCommandHandler implements CommandHandler
{
    public function __construct(private readonly CreateFooService $createFooService)
    {
    }

    public function __invoke(CreateFooCommand $command): void
    {
        $this->createFooService->execute(FooId::fromString($command->id), $command->name);
    }
}
