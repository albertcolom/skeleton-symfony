<?php

declare(strict_types=1);

namespace App\Shared\Domain\Write;

use Webmozart\Assert\Assert;

/**
 * @template TKey of array-key
 * @template T
 * @template-extends Collection<TKey, T>
 */
abstract class TypedCollection extends Collection
{
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
