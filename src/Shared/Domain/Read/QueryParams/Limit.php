<?php

declare(strict_types=1);

namespace App\Shared\Domain\Read\QueryParams;

use Webmozart\Assert\Assert;

final readonly class Limit
{
    private const DEFAULT_VALUE = -1;

    public function __construct(public int $value = self::DEFAULT_VALUE)
    {
        $this->guard();
    }

    private function guard(): void
    {
        if (self::DEFAULT_VALUE === $this->value) {
            return;
        }

        Assert::greaterThan($this->value, 0);
    }
}
