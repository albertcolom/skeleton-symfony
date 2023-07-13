<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Create;

use App\Context\Foo\Domain\Write\ValueObject\FooId;
use App\Shared\Application\Bus\Command\CommandHandler;

final readonly class CreateFooCommandHandler implements CommandHandler
{
    public function __construct(private CreateFooService $createFooService)
    {
    }

    public function __invoke(CreateFooCommand $command): void
    {
        $this->createFooService->execute(FooId::fromString($command->id), $command->name);
    }
}
