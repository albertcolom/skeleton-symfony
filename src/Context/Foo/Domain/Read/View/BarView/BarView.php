<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Read\View\BarView;

use App\Shared\Domain\Read\View\View;

class BarView implements View
{
    public function __construct(public readonly string $id, public readonly string $name)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
