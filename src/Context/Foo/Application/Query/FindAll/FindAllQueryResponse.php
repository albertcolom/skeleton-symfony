<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Domain\Foo;
use App\Shared\Domain\Bus\Query\Response;

class FindAllQueryResponse implements Response
{
    public function __construct(private string $fooId, private string $name)
    {
    }

    public static function fromFoo(Foo $foo): self
    {
        return new self($foo->fooId()->value(), $foo->name());
    }

    public function fooId(): string
    {
        return $this->fooId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function result(): array
    {
        return [
            'foo_id' => $this->fooId,
            'name' => $this->name
        ];
    }
}
