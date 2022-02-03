<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Update;

use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Command\CommandHandler;

class UpdateFooCommandHandler implements CommandHandler
{
    public function __construct(private FooRepository $fooRepository)
    {
    }

    public function __invoke(UpdateFooCommand $command): void
    {
        $foo = $this->fooRepository->findById(FooId::fromString($command->id()));

        if (is_null($foo)) {
            throw FooNotFoundException::fromFooId($command->id());
        }

        $this->fooRepository->save($foo->update($command->name()));
    }
}
