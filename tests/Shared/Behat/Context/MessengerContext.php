<?php

declare(strict_types=1);

namespace App\Tests\Shared\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpTransport;

final class MessengerContext extends KernelTestCase implements Context
{
    /**
     * @When the queue associated to transport :name is empty
     */
    public function theQueueAssociatedToTransportIsEmpty(string $name): void
    {
        $this->purgeTransport($name);

        Assert::assertEquals(0, $this->getTransport($name)->getMessageCount());
    }

    /**
     * @Then the transport :name producer has messages below:
     */
    public function theTransportProducerHasMessagesBelow(string $name, PyStringNode $body): void
    {
        self::assertEquals(
            $this->getJsonFromString($body->getRaw()),
            $this->getMessagesFromTransport($name)
        );
    }

    /**
     * @Then :count messages should have been sent to the transport :name with JSON message:
     */
    public function messageHaveBeenSentToTheTransport2(int $count, string $name, PyStringNode $body): void
    {
        self::assertEquals(
            $this->getJsonFromString($body->getRaw()),
            $this->getMessagesFromTransport($name)
        );
    }

    private function getMessagesFromTransport(string $name): string
    {
        $messages = [];
        $transport = $this->getTransport($name);

        while ($transport->getMessageCount() === 0) {
            echo 'Waiting message';
        }

        foreach ($transport->get() as $envelop) {
            $transport->ack($envelop);
            $messages[] = $envelop->getMessage();
        }

        return json_encode($messages, JSON_THROW_ON_ERROR);
    }

    private function purgeTransport(string $name): void
    {
        $transport = $this->getTransport($name);

        foreach ($transport->get() as $envelop) {
            $transport->reject($envelop);
        }

        if ($transport->getMessageCount() !== 0) {
            $this->purgeTransport($name);
        }
    }

    private function getTransport(string $name): AmqpTransport
    {
        /* @var AmqpTransport $transport */
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
}
