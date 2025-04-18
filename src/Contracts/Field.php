<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Contracts;

interface Field
{
    public function toString(): string;
}
