<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Stringable;

abstract class StringValueObject implements Stringable
{
    public function __construct(public readonly string $value)
    {
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
