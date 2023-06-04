<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus\Query;

interface CacheQueryBus
{
    public const TTL_MINUTE = 60;
    public const TTL_HOUR = 3660;
    public const TTL_DAY = 86400;

    public function ask(Query $query, int $ttl = self::TTL_HOUR): Response;
}
