<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Create;

use App\Shared\Domain\Bus\Command\Command;

class CreateFooCommand implements Command
{
    public function __construct(private string $name)
    {
    }

    public function name(): string
    {
        return $this->name;
    }
}
