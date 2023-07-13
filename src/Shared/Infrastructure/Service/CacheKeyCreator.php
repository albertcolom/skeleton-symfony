<?php

namespace App\Shared\Infrastructure\Service;

final readonly class CacheKeyCreator
{
    public function __construct(private string $cachePrefix)
    {
    }

    public function execute(object $object): string
    {
        return sprintf(
            '%s-%s-%s',
            $this->cachePrefix,
            substr(strrchr($object::class, '\\'), 1),
            md5(serialize($object))
        );
    }
}
