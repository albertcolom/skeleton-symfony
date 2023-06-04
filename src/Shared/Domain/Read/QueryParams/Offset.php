<?php

declare(strict_types=1);

namespace App\Shared\Domain\Read\QueryParams;

use Webmozart\Assert\Assert;

final class Offset
{
    private const DEFAULT_VALUE = 0;

    public function __construct(public readonly int $value = self::DEFAULT_VALUE)
    {
        Assert::greaterThanEq($value, self::DEFAULT_VALUE);
    }
}
