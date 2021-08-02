<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindById;

use App\Shared\Domain\Bus\Query\Query;

class FindByIdQuery implements Query
{
    public function __construct(private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
