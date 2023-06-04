<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Repository\Read;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Bar\ValueObject\BarId;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\FooCollection;
use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Read\QueryParams\QueryParams;
use DateTimeImmutable;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;

final class ElasticSearchFooViewRepository implements FooViewRepository
{
    public function __construct(private readonly Client $client, private readonly string $fooIndex)
    {
    }

    public function findById(FooId $fooId): ?Foo
    {
        $params = [
            'index' => $this->fooIndex,
            'id' => $fooId->value,
        ];

        try {
            $resultSet = $this->client->get($params);
        } catch (ClientResponseException $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }
            return null;
        }

        return $this->hydrate($resultSet->asArray());
    }

    public function findAll(QueryParams $queryParams): FooCollection
    {
        $params = [
            'index' => $this->fooIndex,
            'from' => $queryParams->offset()->value
        ];

        if ($queryParams->hasLimit()) {
            $params = array_merge($params, ['size' => $queryParams->limit()->value]);
        }

        try {
            $resultSet = $this->client->search($params);
        } catch (ClientResponseException $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }
            return FooCollection::createEmpty();
        }

        if (0 === $resultSet['hits']['total']['value']) {
            return FooCollection::createEmpty();
        }

        return $this->hydrateAll($resultSet->asArray());
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
        return FooCollection::fromMap(
            $data['hits']['hits'],
            function (array $data): Foo {
                return $this->hydrate($data);
            }
        );
    }
}
