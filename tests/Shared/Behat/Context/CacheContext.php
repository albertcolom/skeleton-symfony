<?php

declare(strict_types=1);

namespace App\Tests\Shared\Behat\Context;

use Behat\Behat\Context\Context;
use Psr\Cache\CacheItemPoolInterface;

final class CacheContext implements Context
{
    public function __construct(private CacheItemPoolInterface $cache, private string $cachePrefix)
    {
    }

    /**
     * @BeforeScenario
     */
    public function clearCache(): void
    {
        $this->cache->clear($this->cachePrefix);
    }
}
