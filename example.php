<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Performing\FeedBuilder\Feed;
use Performing\FeedBuilder\Formatters\GoogleFeedFormatter;
use Performing\FeedBuilder\Objects\Availability;
use Performing\FeedBuilder\Objects\Condition;
use Performing\FeedBuilder\Objects\Price;
use Performing\FeedBuilder\Objects\Product;
use Performing\FeedBuilder\Objects\Url;
use Performing\FeedBuilder\Writers\StreamWriter;

$feed = new Feed([
    new Product(
        'test-product-1',
        'Test Product',
        'This is a test product description',
        Availability::InStock,
        Condition::New,
        new Price('19.99', 'USD'),
        new Url('https://example.com/product'),
        new Url('https://example.com/product.jpg'),
        'Test Brand',
    ),
]);

$writer = new StreamWriter(fopen('php://stdout', 'w'));
$formatter = new GoogleFeedFormatter($writer);
$formatter->format($feed);
