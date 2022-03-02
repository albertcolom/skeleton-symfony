<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Listener;

use App\Context\Foo\Application\Service\FooIndexUpdater;
use App\Context\Foo\Domain\FooWasRemoved;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Event\EventListener;

class OnFooWasRemoved implements EventListener
{
    public function __construct(private FooIndexUpdater $fooIndexUpdater)
    {
    }

    public function __invoke(FooWasRemoved $event): void
    {
        $this->fooIndexUpdater->execute(FooId::fromString($event->fooId()));

        echo 'OnFooWasRemoved update foo index: ' . $event->fooId() . "\n";
    }
}
