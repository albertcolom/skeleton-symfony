<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Delete;

use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Command\CommandHandler;

class DeleteFooCommandHandler implements CommandHandler
{
    public function __construct(private DeleteFooService $deleteFooService)
    {
    }

    public function __invoke(DeleteFooCommand $command): void
    {
        $this->deleteFooService->execute(FooId::fromString($command->id()));
    }
}
