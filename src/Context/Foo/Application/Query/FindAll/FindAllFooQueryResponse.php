<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Domain\Read\View\FooViewCollection;
use App\Shared\Application\Bus\Query\Response;

final class FindAllFooQueryResponse implements Response
{
    public function __construct(private readonly FooViewCollection $fooViewCollection)
    {
    }

    public static function fromFooViewCollection(FooViewCollection $fooViewCollection): self
    {
        return new self($fooViewCollection);
    }

    public function result(): array
    {
        return $this->fooViewCollection->toArray();
    }
}
