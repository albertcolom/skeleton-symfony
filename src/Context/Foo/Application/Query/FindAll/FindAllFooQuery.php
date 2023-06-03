<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Shared\Domain\Bus\Query\Query;

final class FindAllFooQuery implements Query
{
    public function __construct(public readonly array $params)
    {
    }
}
