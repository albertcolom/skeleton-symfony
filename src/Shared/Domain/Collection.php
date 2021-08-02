<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;

class Collection implements Countable, IteratorAggregate
{
    public function __construct(protected array $elements = [])
    {
    }

    public function add(mixed $element): void
    {
        $this->elements[] = $element;
    }

    public function remove(int $key): mixed
    {
        if (!array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    public function each(Closure $func): self
    {
        return new self(array_map($func, $this->elements));
    }

    public function filter(Closure $func): self
    {
        return new self(array_filter($this->elements, $func, ARRAY_FILTER_USE_BOTH));
    }

    public function element(int $index): mixed
    {
        return $this->elements[$index] ?? null;
    }

    public function elements(): array
    {
        return $this->elements;
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements());
    }

    public function count(): int
    {
        return count($this->elements);
    }
}
