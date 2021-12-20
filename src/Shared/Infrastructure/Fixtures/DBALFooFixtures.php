<?php

namespace App\Shared\Infrastructure\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use InvalidArgumentException;

class DBALFooFixtures extends Fixture implements FixtureGroupInterface
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
INSERT INTO foo (id, name)
VALUES
	(UUID_TO_BIN('7f590fc8-1298-4fb7-927e-a38ae50bc705'), 'Some Foo name 1'),
	(UUID_TO_BIN('1ca06159-6f66-45c6-aa80-1cf5141f66d6'), 'Some Foo name 2'),
	(UUID_TO_BIN('782416f0-5d50-4478-821a-48e5d1f0391d'), 'Some Foo name 3'),
	(UUID_TO_BIN('a557c2ab-b48b-4a02-acda-570d3de4b154'), 'Some Foo name 4'),
	(UUID_TO_BIN('6b7dde86-52c3-45d2-a623-f6bc6f142e29'), 'Some Foo name 5')
SQL;

        $stmt = $connection->prepare($query);
        $stmt->executeQuery();
    }
}
