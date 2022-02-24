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

        return new Envelope((object) $body, []);
    }
}
