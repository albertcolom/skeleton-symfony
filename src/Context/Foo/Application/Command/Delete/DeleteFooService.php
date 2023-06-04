<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Delete;

use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Context\Foo\Domain\Write\Repository\FooRepository;

class DeleteFooService
{
    public function __construct(private readonly FooRepository $fooRepository)
    {
    }

    public function execute(FooId $fooId): void
    {
        $foo = $this->fooRepository->findById($fooId);

        if (is_null($foo)) {
            throw FooNotFoundException::fromFooId($fooId->value);
        }

        $foo->remove();
        $this->fooRepository->remove($fooId);
    }
}
