<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatter;

use Performing\FeedBuilder\Objects\Product;

interface ProductNormaliserInterface
{
    public function normalise(Product $product): array;

    public function isSupported(string $marketingPlatform): bool;
}
