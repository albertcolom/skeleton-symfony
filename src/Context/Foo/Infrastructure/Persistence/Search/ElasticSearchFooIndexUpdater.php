<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Search;

use App\Context\Foo\Application\Service\FooIndexUpdater;
use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use Elasticsearch\Client;

class ElasticSearchFooIndexUpdater implements FooIndexUpdater
{
    public function __construct(private FooRepository $fooRepository, private Client $client, private string $fooIndex)
    {
    }

    public function execute(FooId $fooId): void
    {
        $foo = $this->fooRepository->findById($fooId);

        if (is_null($foo)) {
            throw FooNotFoundException::fromFooId($fooId->value());
        }

        $this->client->index($this->prepareParams($foo));
    }

    private function prepareParams(Foo $foo): array
    {
        return [
            'index' => $this->fooIndex,
            'id' => $foo->fooId()->value(),
            'body' => [
                'name' => $foo->name(),
                'created_at' => $foo->createdAt()->format('Y-m-d H:i:s'),
                'bar' => array_map(
                    static function (Bar $bar) {
                        return [
                            'id' => $bar->barId()->value(),
                            'name' => $bar->name()
                        ];
                    },
                    $foo->bars()->toArray()
                )
            ]
        ];
    }
}
