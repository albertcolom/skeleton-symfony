<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Listener;

use App\Context\Foo\Application\Service\CacheFooRemover;
use App\Context\Foo\Application\Service\FooIndexRemover;
use App\Context\Foo\Domain\Write\Event\FooWasRemoved;
use App\Context\Foo\Domain\Write\ValueObject\FooId;
use App\Shared\Application\Bus\Event\EventListener;

final readonly class OnFooWasRemoved implements EventListener
{
    public function __construct(
        private FooIndexRemover $fooIndexRemover,
        private CacheFooRemover $cacheFooRemover
    ) {
    }

    public function __invoke(FooWasRemoved $event): void
    {
        $fooId = FooId::fromString($event->fooId);

        $this->fooIndexRemover->execute($fooId);
        $this->cacheFooRemover->execute($fooId);

        echo 'OnFooWasRemoved foo: ' . $event->fooId . "\n";
    }
}
