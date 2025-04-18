<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Objects;

use Performing\FeedBuilder\Contracts\Field;

enum Condition: string implements Field
{
    case New = 'new';
    case Refurbished = 'refurbished';
    case Used = 'used';

    public function toString(): string
    {
        return $this->value;
    }
}
