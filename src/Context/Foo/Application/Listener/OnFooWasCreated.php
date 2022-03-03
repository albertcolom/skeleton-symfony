<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Listener;

use App\Context\Foo\Application\Service\CacheFooRemover;
use App\Context\Foo\Application\Service\FooIndexUpdater;
use App\Context\Foo\Domain\Event\FooWasCreated;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Bus\Event\EventListener;

class OnFooWasCreated implements EventListener
{
    public function __construct(private FooIndexUpdater $fooIndexUpdater, private CacheFooRemover $cacheFooRemover)
    {
    }

    public function __invoke(FooWasCreated $event): void
    {
        $fooId = FooId::fromString($event->fooId());

        $this->fooIndexUpdater->execute($fooId);
        $this->cacheFooRemover->execute($fooId);

        echo 'OnFooWasCreated foo: ' . $event->fooId() . "\n";
    }
}
