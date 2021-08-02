<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Serializer;

use App\Shared\Domain\Bus\Event\DomainEvent;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Webmozart\Assert\Assert;

class MessengerDomainEventJsonSerializer implements SerializerInterface
{
    private const CLASS_SEPARATOR = '.';

    public function decode(array $encodedEnvelope): Envelope
    {
        $this->assertValidMessage($encodedEnvelope);

        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];

        $data = json_decode($body, true) ?? [];

        $this->assertValidMessageData($data);

        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }

        /** @var DomainEvent $eventClass */
        $eventClass = $this->decodeMessageType($data['metadata']['type']);

        $domainEvent = $eventClass::fromPrimitives(
            $data['payload']['aggregate_root_id'],
            $data['payload'],
            $data['metadata']['event_id'],
            $data['payload']['occurred_on'],
        );

        return new Envelope($domainEvent, $stamps);
    }

    public function encode(Envelope $envelope): array
    {
        /** @var DomainEvent $message */
        $message = $envelope->getMessage();

        Assert::isInstanceOf($message, DomainEvent::class);

        $data = [
            'payload' =>
                array_merge(
                    ['aggregate_root_id' => $message->aggregateId()->value(),],
                    $message->toPrimitives(),
                    ['occurred_on' => $message->occurredOn()->format('Y-m-d H:i:s')]
                ),
            'metadata' => [
                'event_id' => $message->eventId()->value(),
                'type' => $this->encodeMessageType($message::class),
                'version' => $message->version(),
            ]
        ];

        $allStamps = [];
        foreach ($envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, $stamps);
        }

        return [
            'body' => json_encode($data),
            'headers' => [
                'stamps' => serialize($allStamps),
            ],
        ];
    }

    private function assertValidMessage(array $encodedEnvelope): void
    {
        Assert::keyExists($encodedEnvelope, 'headers');
        Assert::keyExists($encodedEnvelope, 'body');
    }

    private function assertValidMessageData(mixed $data): void
    {
        Assert::keyExists($data, 'metadata');
        Assert::keyExists($data, 'payload');
        Assert::keyExists($data['payload'], 'aggregate_root_id');
        Assert::uuid($data['payload']['aggregate_root_id']);
        Assert::keyExists($data['payload'], 'occurred_on');
        Assert::regex(
            $data['payload']['occurred_on'],
            '/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/'
        );
        Assert::keyExists($data['metadata'], 'type');
        Assert::classExists($this->decodeMessageType($data['metadata']['type']));
        Assert::keyExists($data['metadata'], 'event_id');
        Assert::uuid($data['metadata']['event_id']);
    }

    private function decodeMessageType(string $message): string
    {
        $test = array_map(static function (string $part) {
            return ucwords($part, '_');
        }, explode(self::CLASS_SEPARATOR, $message));

        return str_replace('_', '', implode('\\', $test));
    }

    private function encodeMessageType(string $message): string
    {
        return strtolower(
            preg_replace(
                '/([a-z])([A-Z])/',
                '$1_$2',
                str_replace('\\', self::CLASS_SEPARATOR, $message)
            )
        );
    }
}
