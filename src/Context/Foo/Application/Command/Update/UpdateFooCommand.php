<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Update;

use App\Shared\Application\Bus\Command\Command;

final readonly class UpdateFooCommand implements Command
{
    public function __construct(public string $id, public string $name)
    {
    }
}
