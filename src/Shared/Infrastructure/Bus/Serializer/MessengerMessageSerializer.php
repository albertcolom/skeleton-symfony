<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Serializer;

use App\Shared\Infrastructure\Bus\MessengerIdStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

class MessengerMessageSerializer implements SerializerInterface
{
    private const CLASS_SEPARATOR = '.';

    private Serializer $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(
            [
                new DateTimeNormalizer(),
                new PropertyNormalizer(
                    null,
                    new CamelCaseToSnakeCaseNameConverter()
                ),
            ],
            [
                new JsonEncoder(),
            ]
        );
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $headers = $encodedEnvelope['headers'];
        $body = json_decode($encodedEnvelope['body'], true, 512, JSON_THROW_ON_ERROR);
        $name = $this->decodeMessageName($body['metadata']['name']);

        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }

        $message = $this->serializer->deserialize(json_encode($body['payload']), $name, JsonEncoder::FORMAT);

        return new Envelope($message, $stamps);
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        $allStamps = [];
        foreach ($envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, $stamps);
        }

        /** @var ?MessengerIdStamp $id */
        $id = $envelope->last(MessengerIdStamp::class);

        $data = $this->serializer->serialize([
            'payload' => $message,
            'metadata' => [
                'id' => $id?->value(),
                'name' => $this->encodeMessageName($message::class),
            ]
        ], JsonEncoder::FORMAT);

        return [
            'body' => $data,
            'headers' => [
                'stamps' => serialize($allStamps),
            ],
        ];
    }

    private function decodeMessageName(string $message): string
    {
        $test = array_map(static function (string $part) {
            return ucwords($part, '_');
        }, explode(self::CLASS_SEPARATOR, $message));

        return str_replace('_', '', implode('\\', $test));
    }

    private function encodeMessageName(string $message): string
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
