<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Objects;

use Performing\FeedBuilder\Contracts\Field;

enum Availability: string implements Field
{
    case InStock = 'in stock';
    case OutOfStock = 'out of stock';

    public function toString(): string
    {
        return $this->value;
    }
}
