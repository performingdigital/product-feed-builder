<?php

declare(strict_types=1);

use Performing\FeedBuilder\Feed;
use Performing\FeedBuilder\Formatters\GoogleFeedFormatter;
use Performing\FeedBuilder\Objects\Availability;
use Performing\FeedBuilder\Objects\Condition;
use Performing\FeedBuilder\Objects\Price;
use Performing\FeedBuilder\Objects\Product;
use Performing\FeedBuilder\Objects\Url;

beforeEach(function() {
    $this->tempFile = sys_get_temp_dir() . '/google-feed-test-' . uniqid() . '.xml';
});

afterEach(function() {
    if (file_exists($this->tempFile)) {
        unlink($this->tempFile);
    }
});

test('GoogleFeedFormatter creates a valid XML file', function () {
    // Create a product
    $product = new Product(
        'test-product-1',
        'Test Product',
        'This is a test product description',
        Availability::InStock,
        Condition::New,
        new Price('19.99', 'USD'),
        new Url('https://example.com/product'),
        new Url('https://example.com/product.jpg'),
        'Test Brand'
    );
    
    // Create a feed with the product
    $feed = new Feed([$product]);
    
    // Format the feed
    $formatter = new GoogleFeedFormatter($this->tempFile);
    $formatter->format($feed);
    
    // Check that the file exists
    expect(file_exists($this->tempFile))->toBeTrue();
    
    // Load the XML file
    $xml = simplexml_load_file($this->tempFile);
    
    // Check that it's a valid RSS feed
    expect($xml->getName())->toBe('rss');
    expect($xml->channel)->not->toBeNull();
    
    // Check that there's exactly one item
    expect($xml->channel->item->count())->toBe(1);
    
    // Check product data in XML
    $item = $xml->channel->item[0];
    $ns = $item->getNamespaces(true);
    $g = $item->children($ns['g']);
    
    expect((string)$g->id)->toBe('test-product-1');
    expect((string)$item->title)->toBe('Test Product');
    expect((string)$item->description)->toBe('This is a test product description');
    expect((string)$item->link)->toBe('https://example.com/product');
    expect((string)$g->image_link)->toBe('https://example.com/product.jpg');
    expect((string)$g->availability)->toBe('in stock');
    expect((string)$g->price)->toBe('19.99 USD');
    expect((string)$g->brand)->toBe('Test Brand');
    expect((string)$g->condition)->toBe('new');
});

test('GoogleFeedFormatter handles multiple products', function () {
    // Create a few products
    $products = [
        new Product(
            'test-product-1',
            'Test Product 1',
            'This is test product 1',
            Availability::InStock,
            Condition::New,
            new Price('19.99', 'USD'),
            new Url('https://example.com/product1'),
            new Url('https://example.com/product1.jpg'),
            'Test Brand'
        ),
        new Product(
            'test-product-2',
            'Test Product 2',
            'This is test product 2',
            Availability::OutOfStock,
            Condition::Refurbished,
            new Price('29.99', 'USD'),
            new Url('https://example.com/product2'),
            new Url('https://example.com/product2.jpg'),
            'Test Brand'
        ),
    ];
    
    // Create a feed with the products
    $feed = new Feed($products);
    
    // Format the feed
    $formatter = new GoogleFeedFormatter($this->tempFile);
    $formatter->format($feed);
    
    // Load the XML file
    $xml = simplexml_load_file($this->tempFile);
    
    // Check that there are exactly two items
    expect($xml->channel->item->count())->toBe(2);
});

test('GoogleFeedFormatter includes optional fields when provided', function () {
    // Create a product with optional fields
    $product = new Product(
        'test-product-1',
        'Test Product',
        'This is a test product description',
        Availability::InStock,
        Condition::New,
        new Price('19.99', 'USD'),
        new Url('https://example.com/product'),
        new Url('https://example.com/product.jpg'),
        'Test Brand',
        new Price('15.99', 'USD'), // sale price
        null,
        '123', // Google product category
        null,
        10 // inventory
    );
    
    // Create a feed with the product
    $feed = new Feed([$product]);
    
    // Format the feed
    $formatter = new GoogleFeedFormatter($this->tempFile);
    $formatter->format($feed);
    
    // Load the XML file
    $xml = simplexml_load_file($this->tempFile);
    $item = $xml->channel->item[0];
    $ns = $item->getNamespaces(true);
    $g = $item->children($ns['g']);
    
    // Check optional fields
    expect((string)$g->sale_price)->toBe('15.99 USD');
    expect((string)$g->google_product_category)->toBe('123');
    expect((string)$g->inventory)->toBe('10');
});