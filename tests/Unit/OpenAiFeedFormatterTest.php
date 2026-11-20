<?php

declare(strict_types=1);

use Performing\FeedBuilder\Feed;
use Performing\FeedBuilder\Formatters\OpenAiFeedFormatter;
use Performing\FeedBuilder\Objects\Availability;
use Performing\FeedBuilder\Objects\Condition;
use Performing\FeedBuilder\Objects\Price;
use Performing\FeedBuilder\Objects\Product;
use Performing\FeedBuilder\Objects\Url;
use Performing\FeedBuilder\Writers\CsvStreamWriter;

/**
 * Read a CSV back into rows keyed by column name
 */
function readCsv(string $csv): array
{
    $stream = fopen('php://memory', 'w+');
    fwrite($stream, $csv);
    rewind($stream);

    $header = fgetcsv($stream, null, ',', '"', '');
    $rows = [];

    while (($values = fgetcsv($stream, null, ',', '"', '')) !== false) {
        $rows[] = array_combine($header, $values);
    }

    return $rows;
}

function openAiProduct(string $price = '19.99', ?Price $salePrice = null): Product
{
    return new Product(
        'test-product-1',
        'Test Product',
        'This is a test product description',
        Availability::InStock,
        Condition::Used,
        new Price($price, 'EUR'),
        new Url('https://example.com/product'),
        new Url('https://example.com/product.jpg'),
        'Test Brand',
        $salePrice,
    );
}

test('OpenAiFeedFormatter writes a CSV with the OpenAI columns', function () {
    $csv = (new OpenAiFeedFormatter('Test Seller', 'IT'))->format(new Feed([openAiProduct()]));

    expect(readCsv($csv))->toBe([[
        'item_id' => 'test-product-1',
        'title' => 'Test Product',
        'description' => 'This is a test product description',
        'url' => 'https://example.com/product',
        'brand' => 'Test Brand',
        'image_url' => 'https://example.com/product.jpg',
        'availability' => 'in_stock',
        'price' => '19.99 EUR',
        'seller_name' => 'Test Seller',
        'target_countries' => 'IT',
        'store_country' => 'IT',
        'condition' => 'used',
        'is_ads_eligible' => 'true',
        'sale_price' => '',
        'ads_metadata' => '',
    ]]);
});

test('OpenAiFeedFormatter writes prices with decimals', function () {
    $csv = (new OpenAiFeedFormatter('Test Seller', 'IT'))->format(new Feed([
        openAiProduct('12500', new Price('11900', 'EUR')),
    ]));

    $row = readCsv($csv)[0];

    expect($row['price'])->toBe('12500.00 EUR');
    expect($row['sale_price'])->toBe('11900.00 EUR');
});

test('OpenAiFeedFormatter writes custom fields to ads_metadata', function () {
    $product = openAiProduct()->additional([
        'custom_label_0' => 'Milano',
        'custom_label_1' => '2021',
    ]);

    $csv = (new OpenAiFeedFormatter('Test Seller', 'IT'))->format(new Feed([$product]));

    expect(readCsv($csv)[0]['ads_metadata'])->toBe('{"custom_label_0":"Milano","custom_label_1":"2021"}');
});

test('OpenAiFeedFormatter writes to a stream with CsvStreamWriter', function () {
    $stream = fopen('php://memory', 'w+');

    $result = (new OpenAiFeedFormatter('Test Seller', 'IT', new CsvStreamWriter($stream)))->format(new Feed([openAiProduct()]));

    rewind($stream);

    expect($result)->toBeNull();
    expect(readCsv(stream_get_contents($stream))[0]['item_id'])->toBe('test-product-1');
});
