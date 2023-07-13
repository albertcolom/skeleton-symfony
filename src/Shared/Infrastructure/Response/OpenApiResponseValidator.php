<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Response;

use Exception;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final readonly class OpenApiResponseValidator implements ResponseValidator
{
    private \League\OpenAPIValidation\PSR7\ResponseValidator $responseValidator;

    public function __construct(private ResponseAdapter $responseAdapter, string $openapiPath)
    {
        $this->responseValidator = (new ValidatorBuilder())
            ->fromYamlFile($openapiPath)
            ->getResponseValidator();
    }

    public function validate(string $uri, string $method, Response $response): void
    {
        $psr7Response = $this->responseAdapter->build($response);

        try {
            $this->responseValidator->validate(new OperationAddress($uri, strtolower($method)), $psr7Response);
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }
}
