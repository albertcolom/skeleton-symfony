<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Create;

use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\Repository\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Bus\Event\EventBus;

class CreateFooCommandHandler implements CommandHandler
{
    public function __construct(
        private FooRepository $fooRepository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(CreateFooCommand $command): void
    {
        $foo = Foo::create(FooId::random(), $command->name());
        $this->fooRepository->save($foo);

        $this->eventBus->publish(...$foo->pullDomainEvents());
    }
}
