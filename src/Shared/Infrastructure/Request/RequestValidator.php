<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestValidator
{
    public function validate(Request $request): void;
}
