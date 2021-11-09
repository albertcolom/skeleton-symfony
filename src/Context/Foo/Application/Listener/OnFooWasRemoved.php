<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Listener;

use App\Context\Foo\Application\Query\Find\FindFooQuery;
use App\Context\Foo\Domain\FooWasRemoved;
use App\Shared\Domain\Bus\Event\EventListener;
use App\Shared\Domain\ValueObject\CacheKey;
use Psr\Cache\CacheItemPoolInterface;

class OnFooWasRemoved implements EventListener
{
    public function __construct(private CacheItemPoolInterface $cache)
    {
    }

    public function __invoke(FooWasRemoved $event): void
    {
        $query = new FindFooQuery($event->fooId());
        $this->cache->deleteItem(CacheKey::fromObject($query)->value());

        echo 'OnFooWasRemoved delete cache item with key: ' . CacheKey::fromObject($query) . "\n";
    }
}
