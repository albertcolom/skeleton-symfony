<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Delete;

use App\Shared\Domain\Bus\Command\Command;

class DeleteFooCommand implements Command
{
    public function __construct(public readonly string $id)
    {
    }
}
