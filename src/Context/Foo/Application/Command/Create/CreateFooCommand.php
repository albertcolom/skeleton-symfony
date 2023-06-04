<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Create;

use App\Shared\Application\Bus\Command\Command;

final class CreateFooCommand implements Command
{
    public function __construct(public readonly string $id, public readonly string $name)
    {
    }
}
