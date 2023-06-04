<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Delete;

use App\Context\Foo\Domain\Write\ValueObject\FooId;
use App\Shared\Application\Bus\Command\CommandHandler;

final class DeleteFooCommandHandler implements CommandHandler
{
    public function __construct(private readonly DeleteFooService $deleteFooService)
    {
    }

    public function __invoke(DeleteFooCommand $command): void
    {
        $this->deleteFooService->execute(FooId::fromString($command->id));
    }
}
