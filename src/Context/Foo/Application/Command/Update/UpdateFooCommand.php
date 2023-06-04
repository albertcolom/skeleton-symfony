<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Update;

use App\Shared\Application\Bus\Command\Command;

final class UpdateFooCommand implements Command
{
    public function __construct(public readonly string $id, public readonly string $name)
    {
    }
}
