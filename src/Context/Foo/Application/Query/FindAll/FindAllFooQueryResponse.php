<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\FooCollection;
use App\Shared\Application\Bus\Query\Response;

final class FindAllFooQueryResponse implements Response
{
    public function __construct(private readonly array $foos)
    {
    }

    public static function fromFooCollection(FooCollection $fooCollection): self
    {
        return new self(array_map(
            static function (Foo $foo) {
                return [
                    'id' => $foo->id->value,
                    'name' => $foo->name(),
                    'created_at' => $foo->createdAt()->format('Y-m-d H:i:s'),
                    'bar' => array_map(
                        static function (Bar $bar) {
                            return [
                                'id' => $bar->id->value,
                                'name' => $bar->name
                            ];
                        },
                        $foo->bars()->toArray()
                    )
                ];
            },
            $fooCollection->toArray()
        ));
    }

    public function result(): array
    {
        return $this->foos;
    }
}
