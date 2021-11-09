<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class CacheKey extends StringValueObject
{
    public static function fromObject(object $object): self
    {
        return new self(
            sprintf(
                '%s-%s-%s',
                sprintf('%s%s', $_ENV['APP_ENV'] ?? 'undefined', $_ENV['TEST_TOKEN'] ?? ''),
                substr(strrchr($object::class, '\\'), 1),
                md5(serialize($object))
            )
        );
    }
}
