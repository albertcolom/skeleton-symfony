<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Write\ValueObject\Uuid;
use Symfony\Component\Messenger\Stamp\StampInterface;

final readonly class MessengerIdStamp implements StampInterface
{
    public function __construct(private string $id)
    {
    }

    public static function random(): self
    {
        return new self(Uuid::random()->value);
    }

    public function value(): string
    {
        return $this->id;
    }
}
