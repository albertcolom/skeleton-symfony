<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Shared\Application\Bus\Query\Query;

final readonly class FindAllFooQuery implements Query
{
    /** @param array<string, int> $params */
    public function __construct(public array $params)
    {
    }
}
