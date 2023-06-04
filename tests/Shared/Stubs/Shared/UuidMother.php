<?php

declare(strict_types=1);

namespace App\Tests\Shared\Stubs\Shared;

use Faker\Factory;

final class UuidMother
{
    public static function random(): string
    {
        return Factory::create()->uuid();
    }
}
