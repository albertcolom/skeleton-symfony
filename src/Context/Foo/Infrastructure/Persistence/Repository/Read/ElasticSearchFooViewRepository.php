<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Repository\Read;

use App\Context\Foo\Domain\Exception\FooNotFoundException;
use App\Context\Foo\Domain\Read\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\Read\View\BarView\BarView;
use App\Context\Foo\Domain\Read\View\BarView\BarViewCollection;
use App\Context\Foo\Domain\Read\View\FooView;
use App\Context\Foo\Domain\Read\View\FooViewCollection;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\Read\QueryParams\QueryParams;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;

final class ElasticSearchFooViewRepository implements FooViewRepository
{
    public function __construct(private readonly Client $client, private readonly string $fooIndex)
    {
    }

    public function findById(FooId $fooId): FooView
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
            return throw FooNotFoundException::fromFooId($fooId->value);
        }

        return $this->hydrate($resultSet->asArray());
    }

    public function findAll(QueryParams $queryParams): FooViewCollection
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
            return FooViewCollection::createEmpty();
        }

        if (0 === $resultSet['hits']['total']['value']) {
            return FooViewCollection::createEmpty();
        }

        return $this->hydrateAll($resultSet->asArray());
    }

    private function hydrate(array $data): FooView
    {
        return new FooView(
            $data['_id'] ?? '',
            $data['_source']['name'] ?? '',
            BarViewCollection::fromMap(
                $data['_source']['bar'] ?? [],
                fn(array $data): BarView => new BarView($data['id'] ?? '', $data['name'] ?? '')
            ),
            $data['_source']['created_at'] ?? ''
        );
    }

    private function hydrateAll(array $data): FooViewCollection
    {
        return FooViewCollection::fromMap(
            $data['hits']['hits'] ?? [],
            fn(array $data): FooView => $this->hydrate($data)
        );
    }
}
