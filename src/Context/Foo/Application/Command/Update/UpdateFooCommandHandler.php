<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Update;

use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Command\CommandHandler;

class UpdateFooCommandHandler implements CommandHandler
{
    public function __construct(private UpdateFooService $updateFooService)
    {
    }

    public function __invoke(UpdateFooCommand $command): void
    {
        $this->updateFooService->execute(FooId::fromString($command->id()), $command->name());
    }
}
