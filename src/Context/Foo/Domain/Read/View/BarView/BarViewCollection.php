<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Read\View\BarView;

use App\Shared\Domain\Read\View\View;
use App\Shared\Domain\Read\ViewTypedCollection;

final class BarViewCollection extends ViewTypedCollection implements View
{
    protected function type(): string
    {
        return BarView::class;
    }

    public function toArray(): array
    {
        return $this->map(fn (BarView $barView) => $barView->toArray());
    }
}
