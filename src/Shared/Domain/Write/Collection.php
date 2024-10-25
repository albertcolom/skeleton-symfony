<?php

declare(strict_types=1);

namespace App\Shared\Domain\Write;

use Closure;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @template TKey of array-key
 * @template T
 * @template-extends ArrayCollection<TKey, T>
 */
abstract class Collection extends ArrayCollection
{
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
