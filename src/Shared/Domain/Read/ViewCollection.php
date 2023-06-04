<?php

declare(strict_types=1);

namespace App\Shared\Domain\Read;

class ViewCollection
{
    public function __construct(private array $items)
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
        return array_reduce($this->items, $fn, $initial);
    }

    public function map(callable $fn): array
    {
        return array_map($fn, $this->items);
    }

    public function each(callable $fn): void
    {
        array_walk($this->items, $fn);
    }

    public function some(callable $fn): bool
    {
        foreach ($this->items as $index => $element) {
            if ($fn($element, $index, $this->items)) {
                return true;
            }
        }

        return false;
    }

    public function filter(callable $fn): static
    {
        return new static(array_filter($this->items, $fn, ARRAY_FILTER_USE_BOTH));
    }

    public function first(): mixed
    {
        return reset($this->items);
    }

    public function last(): mixed
    {
        return end($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    public function items(): array
    {
        return $this->items;
    }
}
