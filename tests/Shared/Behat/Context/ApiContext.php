<?php

declare(strict_types=1);

namespace App\Tests\Shared\Behat\Context;

use App\Shared\Infrastructure\Response\ResponseValidator;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

final class ApiContext implements Context
{
    private const WILDCARDS = ['DATETIME', 'UUID'];
    private Response $response;

    public function __construct(private KernelInterface $kernel, private ResponseValidator $responseValidator)
    {
        $this->response = new Response();
    }

    /**
     * @When A :method request is sent to :uri
     */
    public function aRequestIsSent(string $method, string $uri): void
    {
        $this->response = $this->kernel->handle(Request::create($uri, $method));
    }

    /**
     * @When A :method request is sent to :uri with JSON body:
     */
    public function aRequestIsSentWithJsonBody(string $method, string $uri, string $body): void
    {
        $this->response = $this->kernel->handle(
            Request::create(
                $uri,
                $method,
                [],
                [],
                [],
                ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'],
                $this->getJsonFromString($body)
            )
        );
    }

    /**
     * @Then the response should be empty
     */
    public function theResponseShouldBeEmpty(): void
    {
        Assert::assertEmpty($this->response->getContent());
    }

    /**
     * @Then the response code should be :http_status_code
     */
    public function theResponseCodeShouldBe(int $responseCode): void
    {
        Assert::assertEquals($responseCode, $this->response->getStatusCode());
    }

    /**
     * @Then the response header :name should be equal to :value
     */
    public function theHeaderShouldBeEqualTo(string $name, string $value): void
    {
        Assert::assertEquals($value, $this->response->headers->get($name));
    }

    /**
     * @Then the JSON response should be equal to:
     */
    public function theJsonShouldBeEqualTo(PyStringNode $content): void
    {
        [$expected, $response] = $this->replaceWildcards(
            $this->getJsonFromString($content->getRaw()),
            $this->getJsonFromString($this->response->getContent())
        );

        Assert::assertEquals($expected, $response);
    }

    /**
     * @Then the JSON response should be empty
     */
    public function theJsonShouldBeEmpty(): void
    {
        Assert::assertEmpty(json_decode($this->getJsonFromString($this->response->getContent())));
    }

    /**
     * @Then the response should be a documented and validated with OpenApi schema :method :uri
     */
    public function theResponseShouldBeAValidOpenApiSchema(string $uri, string $method): void
    {
        $this->responseValidator->validate($uri, $method, $this->response);
    }

    private function getJsonFromString(string $content): string
    {
        return json_encode(
            json_decode($content, true, 512, JSON_THROW_ON_ERROR),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    private function replaceWildcards(string $expected, string $response): array
    {
        $expected = json_decode($expected, true, 512, JSON_THROW_ON_ERROR);
        $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        foreach (self::WILDCARDS as $wildcard) {
            foreach (array_keys($expected, $wildcard) as $found) {
                $response[$found] = $wildcard;
            }
        }

        return [json_encode($expected), json_encode($response)];
    }
}
