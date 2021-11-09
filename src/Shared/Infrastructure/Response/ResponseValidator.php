<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Response;

use Symfony\Component\HttpFoundation\Response;

interface ResponseValidator
{
    public function validate(string $uri, string $method, Response $response): void;
}
