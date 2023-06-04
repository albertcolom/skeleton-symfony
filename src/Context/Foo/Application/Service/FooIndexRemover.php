<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Service;

use App\Context\Foo\Domain\Write\ValueObject\FooId;

interface FooIndexRemover
{
    public function execute(FooId $fooId): void;
}
