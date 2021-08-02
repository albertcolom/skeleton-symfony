<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\Repository\FooRepository;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Bus\Query\QueryHandler;

class FindAllQueryHandler implements QueryHandler
{
    public function __construct(
        private EventBus $eventBus,
        private FooRepository $fooRepository
    ) {
    }

    public function __invoke(FindAllQuery $query): FindAllQueryResponse
    {
        $foo = $this->fooRepository->findAll();

        $foo = Foo::create('manolo');
        $this->eventBus->publish(...$foo->pullDomainEvents());

        return FindAllQueryResponse::fromFoo($foo);
    }
}
