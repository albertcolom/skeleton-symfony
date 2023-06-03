<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Repository\Write;

use App\Context\Foo\Domain\Foo;
use App\Context\Foo\Domain\FooCollection;
use App\Context\Foo\Domain\Repository\Write\FooRepository;
use App\Context\Foo\Domain\ValueObject\FooId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineFooRepository extends ServiceEntityRepository implements FooRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Foo::class);
    }

    /** @phpstan-ignore-next-line */
    public function findAll(): FooCollection
    {
        $foos = $this->findBy([]);

        if (empty($foos)) {
            return FooCollection::createEmpty();
        }

        return FooCollection::create($foos);
    }

    public function findById(FooId $fooId): ?Foo
    {
        return $this->find($fooId);
    }

    public function save(Foo $foo): void
    {
        $this->_em->persist($foo);
    }

    public function remove(FooId $fooId): void
    {
        $foo = $this->_em->getPartialReference(Foo::class, $fooId);

        $this->_em->remove($foo);
    }
}
