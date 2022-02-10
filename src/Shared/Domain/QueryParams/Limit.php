<?php

declare(strict_types=1);

namespace App\Shared\Domain\QueryParams;

use Webmozart\Assert\Assert;

class Limit
{
    private const DEFAULT_VALUE = -1;

    public function __construct(private int $limit = self::DEFAULT_VALUE)
    {
        $this->guard();
    }

    public static function create(int $limit = self::DEFAULT_VALUE): self
    {
        return new self($limit);
    }

    public function value(): int
    {
        return $this->limit;
    }

    private function guard(): void
    {
        if (self::DEFAULT_VALUE === $this->limit) {
            return;
        }

        Assert::greaterThan($this->limit, 0);
    }
}
