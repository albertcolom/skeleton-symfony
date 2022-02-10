<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Shared\Domain\Bus\Query\Query;

class FindAllFooQuery implements Query
{
    public function __construct(private array $params)
    {
    }

    public function params(): array
    {
        return $this->params;
    }
}
