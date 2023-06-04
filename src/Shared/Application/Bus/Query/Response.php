<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus\Query;

interface Response
{
    public function result(): array;
}
