<?php

declare(strict_types=1);

namespace App\Infrastructure\Collection;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OutOfBoundsException;

use function array_filter;
use function array_map;
use function array_values;
use function count;
use function in_array;

/**
 * @template TKey
 * @template T
 * @template-implements IteratorAggregate<TKey, T>
 */
abstract class Collection implements IteratorAggregate, Countable
{
    /** @param array<TKey, T> $items */
    final public function __construct(
        protected readonly array $items,
    ) {
    }

    /** @return ArrayIterator<array-key, T> */
    final public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    final public function count(): int
    {
        return count($this->items);
    }

    /** @param callable(T): bool $callback */
    final public function filter(callable $callback): static
    {
        return new static(array_filter(
            $this->items,
            $callback,
        ));
    }

    /** @param callable(T): bool $callback */
    final public function map(callable $callback): static
    {
        return new static(array_map(
            $callback,
            $this->items,
        ));
    }

    final public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /** @return T */
    final public function first()
    {
        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Can\'t determine first item. Collection is empty', 1670244881308);
        }

        return array_values($this->items)[0];
    }

    /** @param T $element */
    final public function has($element): bool
    {
        return in_array($element, $this->items, true);
    }

    /** @return array<TKey, T> */
    final public function toArray(): array
    {
        return $this->items;
    }
}
