<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Request;

use Exception;
use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\ServerRequestValidator;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Symfony\Component\HttpFoundation\Request;

class OpenApiRequestValidator implements RequestValidator
{
    private ServerRequestValidator $requestValidator;

    public function __construct(private Psr17RequestFactory $psr17RequestFactory, string $openapiPath)
    {
        $this->requestValidator = (new ValidatorBuilder())
            ->fromYamlFile($openapiPath)
            ->getServerRequestValidator();
    }

    public function validate(Request $request): void
    {
        $psr7Request = $this->psr17RequestFactory->build($request);

        try {
            $this->requestValidator->validate($psr7Request);
        } catch (Exception $exception) {
            if ($exception->getPrevious() instanceof Exception) {
                throw new InvalidArgumentException($exception->getPrevious()->getMessage());
            }
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
