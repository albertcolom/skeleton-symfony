<?php

declare(strict_types=1);

namespace App\Context\Bar\Domain;

use App\Shared\Domain\TypedCollection;

class BarCollection extends TypedCollection
{
    protected function type(): string
    {
        return Bar::class;
    }
}
