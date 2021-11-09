<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Request;

use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;

class Psr17RequestFactory
{
    public function build(object $request): ServerRequestInterface
    {
        if ($request instanceof ServerRequestInterface) {
            return $request;
        }

        if ($request instanceof Request) {
            $psr17Factory = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
            return $psrHttpFactory->createRequest($request);
        }

        throw new InvalidArgumentException(sprintf('Invalid request type: %s', get_class($request)));
    }
}
