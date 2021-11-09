<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Command\Update;

use App\Shared\Domain\Bus\Command\Command;

class UpdateFooCommand implements Command
{
    public function __construct(private string $id, private string $name)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
