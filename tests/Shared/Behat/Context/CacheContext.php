<?php

declare(strict_types=1);

namespace App\Tests\Shared\Behat\Context;

use Behat\Behat\Context\Context;
use Psr\Cache\CacheItemPoolInterface;

final class CacheContext implements Context
{
    public function __construct(private CacheItemPoolInterface $cache)
    {
    }

    /**
     * @BeforeScenario
     */
    public function clearCache(): void
    {
        $prefix = sprintf('%s%s', $_ENV['APP_ENV'] ?? 'undefined', $_ENV['TEST_TOKEN'] ?? '');
        $this->cache->clear($prefix);
    }
}
