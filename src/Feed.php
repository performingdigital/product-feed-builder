<?php

declare(strict_types=1);


namespace Performing\FeedBuilder;

use Performing\FeedBuilder\Objects\Product;

class Feed implements \Countable, \Iterator
{
    /**
     * @param Product[] $products
     */
    public function __construct(
        protected array $products,
    ) {}

    public function current(): mixed
    {
        return current($this->products);
    }

    public function next(): void
    {
        next($this->products);
    }

    public function key(): int|string|null
    {
        return key($this->products);
    }

    public function valid(): bool
    {
        return key($this->products) !== null;
    }

    public function rewind(): void
    {
        reset($this->products);
    }

    public function count(): int
    {
        return count($this->products);
    }
}
