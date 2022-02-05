<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\FindAll;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\FooCollection;
use App\Shared\Domain\Bus\Query\Response;

class FindAllFooQueryResponse implements Response
{
    public function __construct(private array $foos)
    {
    }

    public static function fromFooCollection(FooCollection $fooCollection): self
    {
        return new self(array_map(
            static function (Foo $foo) {
                return [
                    'id' => $foo->fooId()->value(),
                    'name' => $foo->name(),
                    'bar' => array_map(
                        static function (Bar $bar) {
                            return [
                                'id' => $bar->barId()->value(),
                                'name' => $bar->name()
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
