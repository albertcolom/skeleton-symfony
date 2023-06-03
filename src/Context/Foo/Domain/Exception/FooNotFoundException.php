<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Exception;

use DomainException;
use Symfony\Component\HttpFoundation\Response;

final class FooNotFoundException extends DomainException
{
    public static function fromFooId(string $fooId): self
    {
        return new self(sprintf('Foo with id %s not found', $fooId), Response::HTTP_NOT_FOUND);
    }
}
