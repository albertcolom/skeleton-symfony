<?php

declare(strict_types=1);

namespace App\Shared\Domain\QueryParams;

use Webmozart\Assert\Assert;

final class Limit
{
    private const DEFAULT_VALUE = -1;

    public function __construct(public readonly int $value = self::DEFAULT_VALUE)
    {
        $this->guard();
    }

    public static function create(int $limit = self::DEFAULT_VALUE): self
    {
        return new self($limit);
    }

    private function guard(): void
    {
        if (self::DEFAULT_VALUE === $this->value) {
            return;
        }

        Assert::greaterThan($this->value, 0);
    }
}
