<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Performing\FeedBuilder\Examples\FormatterExample;

// Generate Google feed
FormatterExample::generateGoogleFeed(__DIR__ . '/google-feed.xml', 50);

// Generate Facebook feed
FormatterExample::generateFacebookFeed(__DIR__ . '/facebook-feed.csv', 50);