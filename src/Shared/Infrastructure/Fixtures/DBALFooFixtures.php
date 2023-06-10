<?php

namespace App\Shared\Infrastructure\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use InvalidArgumentException;

final class DBALFooFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['foo'];
    }

    public function load(ObjectManager $manager): void
    {
        if (!$manager instanceof EntityManager) {
            throw new InvalidArgumentException('Invalid instance of ObjectManager');
        }

        /* @var Connection $connection */
        $connection = $manager->getConnection();

        $query = <<<SQL
INSERT INTO foo (id, name, created_at)
VALUES
	('7f590fc8-1298-4fb7-927e-a38ae50bc705', 'Some Foo name 1', '2018-01-18 11:11:11'),
	('1ca06159-6f66-45c6-aa80-1cf5141f66d6', 'Some Foo name 2', '2019-01-19 11:11:11'),
	('782416f0-5d50-4478-821a-48e5d1f0391d', 'Some Foo name 3', '2020-01-20 11:11:11'),
	('a557c2ab-b48b-4a02-acda-570d3de4b154', 'Some Foo name 4', '2021-01-21 11:11:11'),
	('6b7dde86-52c3-45d2-a623-f6bc6f142e29', 'Some Foo name 5', '2022-01-22 11:11:11')
SQL;

        $stmt = $connection->prepare($query);
        $stmt->executeQuery();
    }
}
