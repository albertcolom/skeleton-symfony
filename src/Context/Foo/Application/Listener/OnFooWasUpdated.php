<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Listener;

use App\Context\Foo\Application\Service\FooIndexUpdater;
use App\Context\Foo\Domain\FooWasUpdated;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Event\EventListener;

class OnFooWasUpdated implements EventListener
{
    public function __construct(private FooIndexUpdater $fooIndexUpdater)
    {
    }

    public function __invoke(FooWasUpdated $event): void
    {
        $this->fooIndexUpdater->execute(FooId::fromString($event->fooId()));

        echo 'OnFooWasUpdated update foo index: ' . $event->fooId() . "\n";
    }
}
