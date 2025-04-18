<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Objects;

class Url extends AbstractScalarField
{
    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \UnexpectedValueException('Invalid URL');
        }

        parent::__construct($value);
    }
}
