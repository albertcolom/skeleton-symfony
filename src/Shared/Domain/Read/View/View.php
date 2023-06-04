<?php

declare(strict_types=1);

namespace App\Shared\Domain\Read\View;

interface View
{
    public function toArray(): array;
}
