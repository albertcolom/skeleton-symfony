<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class MessengerIdStamp implements StampInterface
{
    public function __construct(private readonly string $id)
    {
    }

    public static function create(): self
    {
        return new self(str_replace('-', '', Uuid::random()->value));
    }

    public function value(): string
    {
        return $this->id;
    }
}
