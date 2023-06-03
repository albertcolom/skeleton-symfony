<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\Find;

use App\Shared\Domain\Bus\Query\Query;

final class FindFooQuery implements Query
{
    public function __construct(public readonly string $id)
    {
    }
}
