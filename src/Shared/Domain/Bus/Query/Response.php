<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Query;

interface Response
{
    public function result(): array;
}
