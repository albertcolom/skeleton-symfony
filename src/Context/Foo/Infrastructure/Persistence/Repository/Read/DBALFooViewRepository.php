<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Repository\Read;

use App\Context\Foo\Domain\Bar\Bar;
use App\Context\Foo\Domain\Bar\ValueObject\BarId;
use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\FooCollection;
use App\Context\Foo\Domain\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use Doctrine\DBAL\Connection;

class DBALFooViewRepository implements FooViewRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function findById(FooId $fooId): ?Foo
    {
        $query = <<<SQL
SELECT
    f.id AS foo_id ,
    f.name AS foo_name,
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

    public function findAll(): FooCollection
    {
        $query = <<<SQL
SELECT
    f.id AS foo_id ,
    f.name AS foo_name,
    b.id AS bar_id,
    b.name AS bar_name
FROM
    foo AS f
    LEFT JOIN bar AS b ON b.foo_id = f.id
SQL;

        $stmt = $this->connection->prepare($query);
        $resultSet = $stmt->executeQuery()->fetchAllAssociative();

        if (empty($resultSet)) {
            return FooCollection::createEmpty();
        }

        return $this->hydrateAll($resultSet);
    }

    private function hydrate(array $data): Foo
    {
        $firstElement = reset($data);
        $foo = new Foo(FooId::fromBinary($firstElement['foo_id']), $firstElement['foo_name']);

        array_walk($data, static function (array $items) use ($foo) {
            if (!is_null($items['bar_id'])) {
                $foo->addBar(new Bar($foo, BarId::fromBinary($items['bar_id']), $items['bar_name']));
            }
        });

        return $foo;
    }

    private function hydrateAll(array $data): FooCollection
    {
        $groupByFoo = array_reduce($data, static function (array $group, array $item) {
            $group[FooId::fromBinary($item['foo_id'])->value()][] = $item;
            return $group;
        }, []);

        return FooCollection::create(array_values(array_map(function (array $data) {
            return $this->hydrate($data);
        }, $groupByFoo)));
    }
}
