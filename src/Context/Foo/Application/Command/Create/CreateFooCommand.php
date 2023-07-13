<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Create;

use App\Shared\Application\Bus\Command\Command;

final readonly class CreateFooCommand implements Command
{
    public function __construct(public string $id, public string $name)
    {
    }
}
