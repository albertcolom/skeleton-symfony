<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Read\View;

use App\Shared\Domain\Read\View\View;
use App\Shared\Domain\Read\ViewTypedCollection;

final class FooViewCollection extends ViewTypedCollection implements View
{
    protected function type(): string
    {
        return FooView::class;
    }

    public function toArray(): array
    {
        return $this->map(fn (FooView $fooView) => $fooView->toArray());
    }
}
