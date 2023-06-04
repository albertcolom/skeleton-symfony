<?php

declare(strict_types=1);

namespace App\Context\Foo\Domain\Write\Event;

use App\Shared\Domain\Write\Event\DomainEvent;

final class BarWasAdded extends DomainEvent
{
    public function __construct(
        public readonly string $fooId,
        public readonly string $barId,
        public readonly string $name,
        ?string $occurredOn = null
    ) {
        parent::__construct($occurredOn);
    }
}
