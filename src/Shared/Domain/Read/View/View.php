<?php

declare(strict_types=1);

namespace App\Shared\Domain\Read\View;

interface View
{
    /** @return array<mixed> */
    public function toArray(): array;
}
