<?php

declare(strict_types=1);

namespace App\Tests\Shared\Context\Foo\Domain\Bar;

use App\Context\Foo\Domain\Bar\ValueObject\BarId;

class BarIdMother
{
    public const DEFAULT_BAR_ID = '35e018f2-ca48-4694-b385-bbb39278482e';

    public static function create(string $value): BarId
    {
        return BarId::fromString($value);
    }

    public static function default(): BarId
    {
        return self::create(self::DEFAULT_BAR_ID);
    }
}
