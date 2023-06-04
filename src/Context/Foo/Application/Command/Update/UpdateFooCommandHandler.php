<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Update;

use App\Context\Foo\Domain\Write\ValueObject\FooId;
use App\Shared\Application\Bus\Command\CommandHandler;

final class UpdateFooCommandHandler implements CommandHandler
{
    public function __construct(private readonly UpdateFooService $updateFooService)
    {
    }

    public function __invoke(UpdateFooCommand $command): void
    {
        $this->updateFooService->execute(FooId::fromString($command->id), $command->name);
    }
}
