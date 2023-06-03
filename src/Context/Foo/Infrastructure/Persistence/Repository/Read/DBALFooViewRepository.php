<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Repository\Read;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Bar\ValueObject\BarId;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\FooCollection;
use App\Context\Foo\Domain\ValueObject\FooId;
use App\Shared\Domain\QueryParams\QueryParams;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

final class DBALFooViewRepository
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function findById(FooId $fooId): ?Foo
    {
        $query = <<<SQL
SELECT
    f.id AS foo_id ,
    f.name AS foo_name,
    f.created_at AS foo_created_at,
    b.id AS bar_id,
    b.name AS bar_name
FROM
    foo AS f
    LEFT JOIN bar AS b ON b.foo_id = f.id
WHERE
    f.id = :id
SQL;

        $stmt = $this->connection->prepare($query);
        $stmt->bindValue('id', $fooId->optimizedId());
        $resultSet = $stmt->executeQuery()->fetchAllAssociative();

        if (empty($resultSet)) {
            return null;
        }

        return $this->hydrate($resultSet);
    }

    public function findAll(QueryParams $queryParams): FooCollection
    {
        $fooCollection = $this->findAllFooWithQueryParams($queryParams);

        return $this->findBarByFooCollection($fooCollection);
    }

    private function findAllFooWithQueryParams(QueryParams $queryParams): FooCollection
    {
        $query = <<<SQL
SELECT
    f.id AS foo_id ,
    f.name AS foo_name,
    f.created_at AS foo_created_at
FROM
    foo AS f
SQL;

        if ($queryParams->hasLimit()) {
            $query .= ' LIMIT :offset , :limit';
        }

        $stmt = $this->connection->prepare($query);

        if ($queryParams->hasLimit()) {
            $stmt->bindValue('offset', $queryParams->offset()->value, ParameterType::INTEGER);
            $stmt->bindValue('limit', $queryParams->limit()->value, ParameterType::INTEGER);
        }

        $resultSet = $stmt->executeQuery()->fetchAllAssociative();

        if (empty($resultSet)) {
            return FooCollection::createEmpty();
        }

        return $this->hydrateFooCollection($resultSet);
    }

    private function findBarByFooCollection(FooCollection $fooCollection): FooCollection
    {
        if ($fooCollection->isEmpty()) {
            return $fooCollection;
        }

        $binValues = [];
        $fooCollection->each(static function (int $key, Foo $foo) use (&$binValues) {
            $binValues[sprintf(':foo_id_%s', $key)] = $foo->id->optimizedId();
            return $foo;
        });

        $query = <<<SQL
SELECT
    b.id  AS bar_id,
    b.foo_id  AS foo_id,
    b.name AS bar_name
FROM
    bar AS b
WHERE
    b.foo_id IN ( :foo_ids )
SQL;

        $prepareQuery = str_replace(':foo_ids', implode(', ', array_keys($binValues)), $query);
        $stmt = $this->connection->prepare($prepareQuery);

        foreach ($binValues as $key => $id) {
            $stmt->bindValue($key, $id);
        }

        $resultSet = $stmt->executeQuery()->fetchAllAssociative();

        if (empty($resultSet)) {
            return $fooCollection;
        }

        return $this->hydrateFooCollectionWithBar($fooCollection, $resultSet);
    }

    private function hydrate(array $data): Foo
    {
        $firstElement = reset($data);
        $foo = new Foo(
            FooId::fromBinary($firstElement['foo_id']),
            $firstElement['foo_name'],
            new DateTimeImmutable($firstElement['foo_created_at'])
        );

        array_walk($data, static function (array $items) use ($foo) {
            if (!is_null($items['bar_id'])) {
                $foo->addBar(new Bar($foo, BarId::fromBinary($items['bar_id']), $items['bar_name']));
            }
        });

        return $foo;
    }

    private function hydrateFooCollection(array $fooData): FooCollection
    {
        return FooCollection::create(
            array_map(static function (array $data): Foo {
                return Foo::create(
                    FooId::fromBinary($data['foo_id']),
                    $data['foo_name'],
                    new DateTimeImmutable($data['foo_created_at'])
                );
            }, $fooData)
        );
    }

    private function hydrateFooCollectionWithBar(FooCollection $fooCollection, array $barData): FooCollection
    {
        if (empty($barData)) {
            return $fooCollection;
        }

        $groupBarByFooId = array_reduce($barData, static function (array $group, array $item) {
            $group[FooId::fromBinary($item['foo_id'])->value][] = $item;
            return $group;
        }, []);

        $fooCollection->each(static function (int $key, Foo $foo) use ($groupBarByFooId) {
            if (array_key_exists($foo->id->value, $groupBarByFooId)) {
                array_walk($groupBarByFooId[$foo->id->value], static function (array $bar) use ($foo) {
                    $foo->addBar(Bar::create($foo, BarId::fromBinary($bar['bar_id']), $bar['bar_name']));
                });
            }
            return $foo;
        });

        return $fooCollection;
    }
}
