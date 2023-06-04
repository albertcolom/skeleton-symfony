<?php

declare(strict_types=1);

namespace App\Shared\Domain\Write\Event;

use DateTimeImmutable;

abstract class DomainEvent
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(private ?string $occurredOn = null)
    {
        $this->occurredOn = $occurredOn ?? (new DateTimeImmutable())->format(self::DATE_FORMAT);
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}
