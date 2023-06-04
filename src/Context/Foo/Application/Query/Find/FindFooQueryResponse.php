<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\Find;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Foo;
use App\Shared\Application\Bus\Query\Response;

final class FindFooQueryResponse implements Response
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $created_at,
        private readonly array $bars
    ) {
    }

    public static function fromFoo(Foo $foo): self
    {
        return new self(
            $foo->id->value,
            $foo->name(),
            $foo->createdAt()->format('Y-m-d H:i:s'),
            array_map(
                static function (Bar $bar) {
                    return [
                        'id' => $bar->id->value,
                        'name' => $bar->name
                    ];
                },
                $foo->bars()->toArray()
            )
        );
    }

    public function result(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'bar' => $this->bars,
        ];
    }
}
