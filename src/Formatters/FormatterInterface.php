<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatters;

use Performing\FeedBuilder\Feed;

interface FormatterInterface
{
    /**
     * Format the feed and save it to the specified location
     */
    public function format(Feed $feed): ?string;
}
