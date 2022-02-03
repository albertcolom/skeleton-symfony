<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Create;

use App\Context\Foo\Domain\Exception\FooAlreadyExistException;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;

class CreateFooService
{
    public function __construct(private FooRepository $fooRepository)
    {
    }

    public function execute(FooId $fooId, string $name): void
    {
        $this->assertFooDoesNotExist($fooId);

        $foo = Foo::create($fooId, $name);
        $this->fooRepository->save($foo);
    }

    private function assertFooDoesNotExist(FooId $fooId): void
    {
        $foo = $this->fooRepository->findById($fooId);

        if (null !== $foo) {
            throw FooAlreadyExistException::fromFooId($fooId->value());
        }
    }
}
