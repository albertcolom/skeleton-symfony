<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use InvalidArgumentException;

class DBALBarFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['bar'];
    }

    public function getDependencies(): array
    {
        return [DBALFooFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        if (!$manager instanceof EntityManager) {
            throw new InvalidArgumentException('Invalid instance of ObjectManager');
        }

        /* @var Connection $connection */
        $connection = $manager->getConnection();

        $query = <<<SQL
INSERT INTO bar (id, foo_id, name)
VALUES
	(UUID_TO_BIN('e4b8fdc9-ded0-4c2f-8c3c-f047e3636655'), UUID_TO_BIN('7f590fc8-1298-4fb7-927e-a38ae50bc705'),
	 'Some Bar name 1'),
	(UUID_TO_BIN('d7b651e9-3bc9-4062-a60b-9882fca29b7f'), UUID_TO_BIN('7f590fc8-1298-4fb7-927e-a38ae50bc705'),
	 'Some Bar name 2'),
	(UUID_TO_BIN('06b433af-5699-4cf2-8fb0-29cca9e694c3'), UUID_TO_BIN('1ca06159-6f66-45c6-aa80-1cf5141f66d6'),
	 'Some Bar name 3'),
	(UUID_TO_BIN('5fef1065-8fe3-4e29-8712-5eb89fdbc0a0'), UUID_TO_BIN('a557c2ab-b48b-4a02-acda-570d3de4b154'),
	 'Some Bar name 4')
SQL;

        $stmt = $connection->prepare($query);
        $stmt->executeQuery();
    }
}
