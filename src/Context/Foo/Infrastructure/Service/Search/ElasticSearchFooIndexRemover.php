<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Service\Search;

use App\Context\Foo\Application\Service\FooIndexRemover;
use App\Context\Foo\Domain\ValueObject\FooId;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class ElasticSearchFooIndexRemover implements FooIndexRemover
{
    public function __construct(private Client $client, private string $fooIndex)
    {
    }

    public function execute(FooId $fooId): void
    {
        $params = [
            'index' => $this->fooIndex,
            'id' => $fooId->value()
        ];

        try {
            $this->client->delete($params);
        } catch (Missing404Exception) {
            return;
        }
    }
}
