<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Repository;

use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\Repository\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineFooRepository extends ServiceEntityRepository implements FooRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Foo::class);
    }

    public function findById(FooId $fooId): ?Foo
    {
        return $this->find($fooId);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function save(Foo $foo): void
    {
        $this->_em->persist($foo);
        $this->_em->flush();
    }
}
