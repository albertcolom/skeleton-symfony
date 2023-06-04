<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Listener;

use App\Context\Foo\Application\Service\CacheFooRemover;
use App\Context\Foo\Application\Service\FooIndexUpdater;
use App\Context\Foo\Domain\Event\FooWasUpdated;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Application\Bus\Event\EventListener;

final class OnFooWasUpdated implements EventListener
{
    public function __construct(
        private readonly FooIndexUpdater $fooIndexUpdater,
        private readonly CacheFooRemover $cacheFooRemover
    ) {
    }

    public function __invoke(FooWasUpdated $event): void
    {
        $fooId = FooId::fromString($event->fooId);

        $this->fooIndexUpdater->execute($fooId);
        $this->cacheFooRemover->execute($fooId);

        echo 'OnFooWasUpdated foo: ' . $event->fooId . "\n";
    }
}
