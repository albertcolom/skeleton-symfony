<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Query\Find;

use App\Context\Foo\Domain\Read\Repository\Read\FooViewRepository;
use App\Context\Foo\Domain\Read\View\FooView;
use App\Context\Foo\Domain\ValueObject\FooId;

class FindFooService
{
    public function __construct(private readonly FooViewRepository $fooViewRepository)
    {
    }

    public function execute(FooId $fooId): FooView
    {
        return $this->fooViewRepository->findById($fooId);
    }
}
