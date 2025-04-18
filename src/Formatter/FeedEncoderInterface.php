<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatter;

use Performing\FeedBuilder\Feed;

/**
 * Supported Facebook feed formats:
 * @link https://developers.facebook.com/docs/marketing-api/catalog/reference#feed-format
 */
interface FeedEncoderInterface
{
    public function encode(Feed $feed, ProductNormaliserInterface $productNormaliser): \Generator;

    public function isSupported(string $marketingPlatform, string $format): bool;
}
