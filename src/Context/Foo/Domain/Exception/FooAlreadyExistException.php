<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Exception;

use DomainException;
use Symfony\Component\HttpFoundation\Response;

class FooAlreadyExistException extends DomainException
{
    public static function fromFooId(string $fooId): self
    {
        return new self(sprintf('Foo with id %s already exists', $fooId), Response::HTTP_CONFLICT);
    }
}
