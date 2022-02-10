<?php

declare(strict_types=1);

namespace App\Shared\Domain\QueryParams;

use Webmozart\Assert\Assert;

class Offset
{
    private const DEFAULT_VALUE = 0;

    public function __construct(private int $offset = self::DEFAULT_VALUE)
    {
        Assert::greaterThanEq($offset, self::DEFAULT_VALUE);
    }

    public static function create(int $offset = self::DEFAULT_VALUE): self
    {
        return new self($offset);
    }

    public function value(): int
    {
        return $this->offset;
    }
}
