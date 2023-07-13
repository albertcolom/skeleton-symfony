<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Read\View\BarView;

use App\Shared\Domain\Read\View\View;

readonly class BarView implements View
{
    public function __construct(public string $id, public string $name)
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
