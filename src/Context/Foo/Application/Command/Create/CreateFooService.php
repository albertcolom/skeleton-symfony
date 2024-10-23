<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Create;

use App\Context\Foo\Domain\Exception\FooAlreadyExistException;
use App\Context\Foo\Domain\Write\Foo;
use App\Context\Foo\Domain\Write\Repository\FooRepository;
use App\Context\Foo\Domain\Write\ValueObject\FooId;
use DateTimeImmutable;

readonly class CreateFooService
{
    public function __construct(private FooRepository $fooRepository)
    {
    }

    public function execute(FooId $fooId, string $name): void
    {
        $this->assertFooDoesNotExist($fooId);

        $foo = new Foo($fooId, $name, new DateTimeImmutable());
        $this->fooRepository->save($foo);
    }

    private function assertFooDoesNotExist(FooId $fooId): void
    {
        $foo = $this->fooRepository->findById($fooId);

        if (null !== $foo) {
            throw FooAlreadyExistException::fromFooId($fooId->value);
        }
    }
}
