<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatters;

use Performing\FeedBuilder\Feed;

/**
 * Interface for feed formatters
 */
interface FormatterInterface
{
    /**
     * Format the feed and save it to the specified location
     * 
     * @param Feed $feed The feed containing products to be formatted
     * @return void
     */
    public function format(Feed $feed): void;
}