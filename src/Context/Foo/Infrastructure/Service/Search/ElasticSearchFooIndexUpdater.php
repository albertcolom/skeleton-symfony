<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Service\Search;

use App\Context\Foo\Application\Service\FooIndexUpdater;
use App\Context\Foo\Domain\Write\Bar\Bar;
use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Write\Foo;
use App\Context\Foo\Domain\Write\Repository\FooRepository;
use App\Context\Foo\Domain\Write\ValueObject\FooId;
use DateTimeImmutable;
use DateTimeInterface;
use Elastic\Elasticsearch\Client;

readonly class ElasticSearchFooIndexUpdater implements FooIndexUpdater
{
    public function __construct(
        private FooRepository $fooRepository,
        private Client $client,
        private string $fooIndex
    ) {
    }

    public function execute(FooId $fooId): void
    {
        $foo = $this->fooRepository->findById($fooId);

        if (is_null($foo)) {
            throw FooNotFoundException::fromFooId($fooId->value);
        }

        $this->client->index($this->prepareParams($foo));
    }

    private function prepareParams(Foo $foo): array
    {
        return [
            'index' => $this->fooIndex,
            'id' => $foo->id->value,
            'body' => [
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
                ),
                '@timestamp' => (new DateTimeImmutable())->format(DateTimeInterface::ISO8601)
            ]
        ];
    }
}
