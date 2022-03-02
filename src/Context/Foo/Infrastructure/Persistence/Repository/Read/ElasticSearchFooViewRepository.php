<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Repository\Read;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Bar\ValueObject\BarId;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\FooCollection;
use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\QueryParams\QueryParams;
use DateTimeImmutable;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class ElasticSearchFooViewRepository implements FooViewRepository
{
    private const INDEX = 'foo';

    public function __construct(private Client $client)
    {
    }

    public function findById(FooId $fooId): ?Foo
    {
        $params = [
            'index' => self::INDEX,
            'id' => $fooId->value(),
        ];

        try {
            $resultSet = $this->client->get($params);
        } catch (Missing404Exception) {
            return null;
        }

        return $this->hydrate($resultSet);
    }

    public function findAll(QueryParams $queryParams): FooCollection
    {
        $params = [
            'index' => self::INDEX,
            'size' => $queryParams->limit()->value(),
            'from' => $queryParams->offset()->value()
        ];

        try {
            $resultSet = $this->client->search($params);
        } catch (Missing404Exception) {
            return FooCollection::createEmpty();
        }

        if (0 === $resultSet['hits']['total']['value']) {
            return FooCollection::createEmpty();
        }

        return $this->hydrateAll($resultSet);
    }

    private function hydrate(array $data): Foo
    {
        $foo = new Foo(
            FooId::fromString($data['_id']),
            $data['_source']['name'],
            new DateTimeImmutable($data['_source']['created_at'])
        );

        array_walk($data['_source']['bar'], static function (array $bar) use ($foo) {
            $foo->addBar(new Bar($foo, BarId::fromString($bar['id']), $bar['name']));
        });

        return $foo;
    }

    private function hydrateAll(array $data): FooCollection
    {
        return FooCollection::create(
            array_map(function (array $data): Foo {
                return $this->hydrate($data);
            }, $data['hits']['hits'])
        );
    }
}
