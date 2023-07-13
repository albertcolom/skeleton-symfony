<?php

declare(strict_types=1);

namespace App\Tests\Shared\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Coduo\PHPMatcher\PHPMatcher;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class MessengerContext extends KernelTestCase implements Context
{
    private PHPMatcher $matcher;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->matcher = new PHPMatcher();
        parent::__construct($name, $data, $dataName);
    }

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
        $actual =  $this->sanitizeJson($this->getMessagesFromTransport($name));
        $expected =$this->sanitizeJson($body->getRaw());

        if (!$this->matcher->match($actual, $expected)) {
            throw new RuntimeException($this->matcher->error());
        }
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

    private function sanitizeJson(string $content): string
    {
        return json_encode(
            json_decode($content, true, 512, JSON_THROW_ON_ERROR),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }
}
