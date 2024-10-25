<?php

declare(strict_types=1);

namespace App\Context\Foo\Infrastructure\Persistence\Repository\Write;

use App\Context\Foo\Domain\Write\Foo;
use App\Context\Foo\Domain\Write\FooCollection;
use App\Context\Foo\Domain\Write\Repository\FooRepository;
use App\Context\Foo\Domain\Write\ValueObject\FooId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Foo> */
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

        return new FooCollection($foos);
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
        $foo = $this->_em->getReference(Foo::class, $fooId);

        $this->_em->remove($foo);
    }
}
