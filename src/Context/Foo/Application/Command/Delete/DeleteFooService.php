<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Delete;

use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;

class DeleteFooService
{
    public function __construct(private FooRepository $fooRepository)
    {
    }

    public function execute(FooId $fooId): void
    {
        $foo = $this->fooRepository->findById($fooId);

        if (is_null($foo)) {
            throw FooNotFoundException::fromFooId($fooId->value());
        }

        $foo->remove();
        $this->fooRepository->remove($fooId);
    }
}
