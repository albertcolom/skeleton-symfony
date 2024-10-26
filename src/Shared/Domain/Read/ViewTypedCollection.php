<?php

declare(strict_types=1);

namespace App\Shared\Domain\Read;

use Webmozart\Assert\Assert;

abstract class ViewTypedCollection extends ViewCollection
{
    /** @param array<mixed> $elements */
    public function __construct(array $elements = [])
    {
        Assert::allIsInstanceOf($elements, $this->type());

        parent::__construct($elements);
    }

    abstract protected function type(): string;

    public function add(mixed $element): void
    {
        Assert::isInstanceOf($element, $this->type());

        parent::add($element);
    }
}
