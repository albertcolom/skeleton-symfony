<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Closure;
use Doctrine\Common\Collections\ArrayCollection;

class Collection extends ArrayCollection
{
    public static function create(array $elements): static
    {
        return new static($elements);
    }

    public static function createEmpty(): static
    {
        return new static([]);
    }

    public static function fromMap(array $items, Closure $func): static
    {
        return new static(array_map($func, $items));
    }

    public function each(Closure $fn): void
    {
        $this->forAll($fn);
    }
}
