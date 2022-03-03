<?php

namespace App\Context\Foo\Infrastructure\Service\Cache;

use App\Context\Foo\Application\Query\Find\FindFooQuery;
use App\Context\Foo\Application\Service\CacheFooRemover;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Infrastructure\Service\CacheKeyCreator;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisCacheFooRemover implements CacheFooRemover
{
    public function __construct(
        private CacheItemPoolInterface $cache,
        private CacheKeyCreator $cacheKeyCreator,
        private string $cachePrefix
    ) {
    }

    public function execute(FooId $fooId): void
    {
        $this->cache->deleteItem($this->cacheKeyCreator->execute(new FindFooQuery($fooId)));

        if ($this->cache instanceof RedisAdapter) {
            $this->cache->clear(sprintf('%s-%s', $this->cachePrefix, 'FindAllFooQuery'));
        }
    }
}
