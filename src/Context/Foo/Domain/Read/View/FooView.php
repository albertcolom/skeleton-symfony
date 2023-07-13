<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Read\View;

use App\Context\Foo\Domain\Read\View\BarView\BarViewCollection;
use App\Shared\Domain\Read\View\View;

readonly class FooView implements View
{
    public function __construct(
        public string $id,
        public string $name,
        public BarViewCollection $barsView,
        public string $createdAt
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bar' => $this->barsView->toArray(),
            'created_at' => $this->createdAt,
        ];
    }
}
