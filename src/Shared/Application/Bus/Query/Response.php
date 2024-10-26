<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus\Query;

interface Response
{
    /** @return array<mixed> */
    public function result(): array;
}
