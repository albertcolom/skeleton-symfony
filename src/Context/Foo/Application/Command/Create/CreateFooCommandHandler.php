<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Create;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Bar\ValueObject\BarId;
use App\Context\Foo\Domain\Exception\FooAlreadyExistException;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\Repository\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Command\CommandHandler;

class CreateFooCommandHandler implements CommandHandler
{
    public function __construct(private FooRepository $fooRepository)
    {
    }

    public function __invoke(CreateFooCommand $command): void
    {
        $fooId = FooId::fromString($command->id());

        $this->assertFooDoesNotExist($fooId);

        $foo = Foo::create($fooId, $command->name());

        //$foo->addBar(Bar::create($foo, BarId::random(), 'fdsfdsfdsfds'));

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
