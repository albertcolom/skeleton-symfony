<?php

declare(strict_types=1);

namespace App\Tests\Shared\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class MessengerContext extends KernelTestCase implements Context
{
    private const WILDCARDS = ['DATETIME', 'UUID'];

    /**
     * @When the queue associated to transport :name is empty
     */
    public function theQueueAssociatedToTransportIsEmpty(string $name): void
    {
        $this->purgeTransport($name);

        Assert::assertEmpty($this->getTransport($name)->get());
    }

    /**
     * @Then the transport :name producer has messages below:
     */
    public function theTransportProducerHasMessagesBelow(string $name, PyStringNode $body): void
    {
        [$expected, $response] = $this->replaceWildcards(
            $this->getJsonFromString($body->getRaw()),
            $this->getJsonFromString($this->getMessagesFromTransport($name))
        );

        Assert::assertEquals($expected, $response);
    }

    private function getMessagesFromTransport(string $name): string
    {
        $messages = [];
        $transport = $this->getTransport($name);

        foreach ($transport->get() as $envelop) {
            $transport->ack($envelop);
            $messages[] = $envelop->getMessage();
        }

        return json_encode($messages, JSON_THROW_ON_ERROR);
    }

    private function purgeTransport(string $name): void
    {
        $transport = $this->getTransport($name);

        while (!empty($message = $transport->get())) {
            foreach ($message as $envelop) {
                $transport->reject($envelop);
            }
        }
    }

    private function getTransport(string $name): TransportInterface
    {
        /* @var TransportInterface $transport */
        $transport = self::getContainer()->get(sprintf('messenger.transport.%s', $name));

        return $transport;
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
            foreach ($expected as $key => $values) {
                foreach ($values as $valueKey => $value) {
                    foreach (array_keys($value, $wildcard) as $found) {
                        $response[$key][$valueKey][$found] = $wildcard;
                    }
                }
            }
        }

        return [json_encode($expected), json_encode($response)];
    }
}
