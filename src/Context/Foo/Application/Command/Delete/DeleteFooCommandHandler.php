<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Delete;

use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Command\CommandHandler;

class DeleteFooCommandHandler implements CommandHandler
{
    public function __construct(private FooRepository $fooRepository)
    {
    }

    public function __invoke(DeleteFooCommand $command): void
    {
        $fooId = FooId::fromString($command->id());

        $foo = $this->fooRepository->findById($fooId);

        if (is_null($foo)) {
            throw FooNotFoundException::fromFooId($command->id());
        }

        $foo->remove();
        $this->fooRepository->remove($fooId);
    }
}
