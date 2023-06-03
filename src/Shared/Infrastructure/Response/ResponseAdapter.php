<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Response;

use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Response;

final class ResponseAdapter
{
    public function build(object $response): ResponseInterface
    {
        if ($response instanceof ResponseInterface) {
            return $response;
        }

        if ($response instanceof Response) {
            $psr17Factory = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
            return $psrHttpFactory->createResponse($response);
        }

        throw new InvalidArgumentException(sprintf('Invalid response type: %s', get_class($response)));
    }
}
