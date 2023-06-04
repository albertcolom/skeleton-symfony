<?php

declare(strict_types=1);

namespace App\Shared\Domain\Write\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Stringable;
use Webmozart\Assert\Assert;

class Uuid implements Stringable
{
    public function __construct(public readonly string $value)
    {
        Assert::uuid($value);
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public static function random(): static
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public function optimizedId(): string
    {
        return RamseyUuid::fromString($this->value)->getBytes();
    }

    public static function fromBinary(string $bytes): static
    {
        return new static(RamseyUuid::fromBytes($bytes)->toString());
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
