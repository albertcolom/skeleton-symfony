<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Update;

use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Write\Repository\FooRepository;
use App\Context\Foo\Domain\Write\ValueObject\FooId;

readonly class UpdateFooService
{
    public function __construct(private FooRepository $fooRepository)
    {
    }

    public function execute(FooId $fooId, string $name): void
    {
        $foo = $this->fooRepository->findById($fooId);

        if (is_null($foo)) {
            throw FooNotFoundException::fromFooId($fooId->value);
        }

        $this->fooRepository->save($foo->update($name));
    }
}
