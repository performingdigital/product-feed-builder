<?php

declare(strict_types=1);

use Performing\FeedBuilder\Objects\Product;
use Performing\FeedBuilder\Objects\Price;
use Performing\FeedBuilder\Objects\Url;
use Performing\FeedBuilder\Objects\Availability;
use Performing\FeedBuilder\Objects\Condition;

/**
 * Test basic object construction and validation
 */
test('product can be constructed with valid data', function() {
    $product = new Product(
        'prod123',
        'Awesome Product',
        'This is an awesome product description',
        Availability::InStock,
        Condition::New,
        new Price('19.99', 'USD'),
        new Url('https://example.com/products/awesome'),
        new Url('https://example.com/images/awesome.jpg'),
        'Awesome Brand'
    );
    
    expect($product)->toBeInstanceOf(Product::class);
    expect($product->getId())->toBe('prod123');
    expect($product->getTitle())->toBe('Awesome Product');
});

/**
 * Test that price validation works
 */
test('price constructor validates amount format', function() {
    expect(fn() => new Price('19,99', 'USD'))
        ->toThrow(InvalidArgumentException::class, 'Amount must be number with "." as decimal point');
});

test('price constructor validates currency format', function() {
    expect(fn() => new Price('19.99', 'US'))
        ->toThrow(InvalidArgumentException::class, 'Currency must be alpha 3 by ISO 4210');
});

/**
 * Test that URL validation works
 */
test('url constructor validates url format', function() {
    expect(fn() => new Url('invalid-url'))
        ->toThrow(UnexpectedValueException::class, 'Invalid URL');
});

/**
 * Test inventory validation
 */
test('product validates inventory is positive', function() {
    $product = new Product(
        'prod123',
        'Awesome Product',
        'This is an awesome product description',
        Availability::InStock,
        Condition::New,
        new Price('19.99', 'USD'),
        new Url('https://example.com/products/awesome'),
        new Url('https://example.com/images/awesome.jpg'),
        'Awesome Brand'
    );
    
    expect(fn() => $product->setInventory(0))
        ->toThrow(OutOfBoundsException::class, 'Inventory must be positive');
    
    expect(fn() => $product->setInventory(-1))
        ->toThrow(OutOfBoundsException::class, 'Inventory must be positive');
    
    // This should work fine
    $product->setInventory(1);
    expect($product->getInventory())->toBe(1);
});

/**
 * Placeholder for future Bag validation tests 
 */
test('future bag implementation', function() {
    $this->markTestSkipped('This test is a placeholder for when Bag library is installed');
    
    // // Create with validation - future code
    // $product = Product::from([
    //     'id' => 'prod123',
    //     'title' => 'Awesome Product',
    //     'description' => 'This is an awesome product description',
    //     'availability' => Availability::InStock,
    //     'condition' => Condition::New,
    //     'price' => Price::from([
    //         'amount' => '19.99',
    //         'iso4210Currency' => 'USD'
    //     ]),
    //     'link' => Url::from(['value' => 'https://example.com/products/awesome']),
    //     'imageLink' => Url::from(['value' => 'https://example.com/images/awesome.jpg']),
    //     'brand' => 'Awesome Brand',
    //     'inventory' => 10,
    // ]);
    // 
    // expect($product)->toBeInstanceOf(Product::class);
    // 
    // // Expect validation to fail due to missing required fields
    // expect(fn() => Product::from([
    //     'id' => 'prod123',
    //     // Missing required fields
    // ]))->toThrow(\Illuminate\Validation\ValidationException::class);
    // 
    // // Should not throw exception even with missing fields
    // $product = Product::withoutValidation([
    //     'id' => 'prod123',
    //     // Missing required fields, but validation is skipped
    // ]);
    // 
    // expect($product)->toBeInstanceOf(Product::class);
    // expect($product->getId())->toBe('prod123');
});