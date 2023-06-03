<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Service\Search;

use App\Context\Foo\Application\Service\FooIndexRemover;
use App\Context\Foo\Domain\ValueObject\FooId;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;

final class ElasticSearchFooIndexRemover implements FooIndexRemover
{
    public function __construct(private readonly Client $client, private readonly string $fooIndex)
    {
    }

    public function execute(FooId $fooId): void
    {
        $params = [
            'index' => $this->fooIndex,
            'id' => $fooId->value
        ];

        try {
            $this->client->delete($params);
        } catch (ClientResponseException $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }
        }
    }
}
