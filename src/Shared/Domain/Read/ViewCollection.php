<?php

declare(strict_types=1);

namespace App\Shared\Domain\Read;

abstract class ViewCollection
{
    public function __construct(private array $elements)
    {
    }

    public static function createEmpty(): static
    {
        return new static([]);
    }

    public static function fromMap(array $items, callable $fn): static
    {
        return new static(array_map($fn, $items));
    }

    public function reduce(callable $fn, mixed $initial): mixed
    {
        return array_reduce($this->elements, $fn, $initial);
    }

    public function map(callable $fn): array
    {
        return array_map($fn, $this->elements);
    }

    public function each(callable $fn): void
    {
        array_walk($this->elements, $fn);
    }

    public function some(callable $fn): bool
    {
        foreach ($this->elements as $index => $element) {
            if ($fn($element, $index, $this->elements)) {
                return true;
            }
        }

        return false;
    }

    public function filter(callable $fn): static
    {
        return new static(array_filter($this->elements, $fn, ARRAY_FILTER_USE_BOTH));
    }

    public function first(): mixed
    {
        return reset($this->elements);
    }

    public function last(): mixed
    {
        return end($this->elements);
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function add(mixed $element): void
    {
        $this->elements[] = $element;
    }

    public function getValues(): array
    {
        return array_values($this->elements);
    }

    public function items(): array
    {
        return $this->elements;
    }
}
