<?php

declare(strict_types=1);

namespace App\Context\Foo\Application\Service;

use App\Context\Foo\Domain\ValueObject\FooId;

interface CacheFooRemover
{
    public function execute(FooId $fooId): void;
}
