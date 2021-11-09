<?php

declare(strict_types=1);

namespace App\Tests\Shared\Behat\Serializer;

use App\Shared\Infrastructure\Bus\Serializer\MessengerMessageSerializer;
use Symfony\Component\Messenger\Envelope;

final class TestingMessengerMessageSerializer extends MessengerMessageSerializer
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = json_decode($encodedEnvelope['body'], true, 512, JSON_THROW_ON_ERROR);

        if (isset($body['payload']['occurred_on'])) {
            $body['payload']['occurred_on'] = 'XXXX-XX-XX XX:XX:XX';
        }

        if (isset($body['metadata']['id'])) {
            $body['metadata']['id'] = 'XXX';
        }

        return new Envelope((object) $body, []);
    }
}
