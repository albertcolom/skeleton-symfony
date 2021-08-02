<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Webmozart\Assert\Assert;

abstract class TypedCollection extends Collection
{
    public function __construct(protected array $elements)
    {
        Assert::allIsInstanceOf($elements, $this->type());

        parent::__construct($elements);
    }

    abstract protected function type(): string;

    public function add(mixed $element): void
    {
        Assert::isInstanceOf($element, $this->type());

        $this->elements[] = $element;
    }
}
