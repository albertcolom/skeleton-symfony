<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\Find;

use App\Context\Foo\Domain\Read\View\BarView\BarViewCollection;
use App\Context\Foo\Domain\Read\View\FooView;
use App\Shared\Application\Bus\Query\Response;

final readonly class FindFooQueryResponse implements Response
{
    public function __construct(
        private string $id,
        private string $name,
        private BarViewCollection $barsView,
        private string $created_at
    ) {
    }

    public static function fromFooView(FooView $fooView): self
    {
        return new self($fooView->id, $fooView->name, $fooView->barsView, $fooView->createdAt);
    }

    public function result(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bar' => $this->barsView->toArray(),
            'created_at' => $this->created_at,
        ];
    }
}
