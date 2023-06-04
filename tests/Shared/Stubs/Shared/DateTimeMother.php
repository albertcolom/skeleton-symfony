<?php

declare(strict_types=1);

namespace App\Tests\Shared\Stubs\Shared;

use DateTime;
use Faker\Factory;

final class DateTimeMother
{
    private const DEFAULT_FORMAT = 'Y-m-d H:i:s';

    public static function random(): DateTime
    {
        return Factory::create()->dateTime;
    }

    public static function randomWithDefaultFormat(): string
    {
        return self::random()->format(self::DEFAULT_FORMAT);
    }
}
