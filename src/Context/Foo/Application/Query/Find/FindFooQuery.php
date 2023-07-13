<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\Find;

use App\Shared\Application\Bus\Query\Query;

final readonly class FindFooQuery implements Query
{
    public function __construct(public string $id)
    {
    }
}
