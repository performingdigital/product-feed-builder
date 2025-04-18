<?php

declare(strict_types=1);

namespace Tests\Objects;

use Performing\FeedBuilder\Objects\Product;
use Performing\FeedBuilder\Objects\Price;
use Performing\FeedBuilder\Objects\Url;
use Performing\FeedBuilder\Objects\Availability;
use Performing\FeedBuilder\Objects\Condition;
use PHPUnit\Framework\TestCase;

/**
 * Tests for validation (placeholder for future Bag implementation)
 */
class ValidationTest extends TestCase
{
    /**
     * Test basic object construction and validation
     */
    public function testObjectConstruction(): void
    {
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
        
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('prod123', $product->getId());
        $this->assertEquals('Awesome Product', $product->getTitle());
    }
    
    /**
     * Test that price validation works
     */
    public function testPriceValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        
        // Should fail because amount format is invalid
        new Price('19,99', 'USD');
    }
    
    /**
     * Test that URL validation works
     */
    public function testUrlValidation(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        
        // Should fail because URL is invalid
        new Url('invalid-url');
    }
    
    /**
     * Placeholder to show how tests will work with Bag library
     */
    public function testFutureBagImplementation(): void
    {
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
        // $this->assertInstanceOf(Product::class, $product);
        // 
        // // Expect validation to fail due to missing required fields
        // $this->expectException(\Illuminate\Validation\ValidationException::class);
        // 
        // Product::from([
        //     'id' => 'prod123',
        //     // Missing required fields
        // ]);
        // 
        // // Should not throw exception even with missing fields
        // $product = Product::withoutValidation([
        //     'id' => 'prod123',
        //     // Missing required fields, but validation is skipped
        // ]);
        // 
        // $this->assertInstanceOf(Product::class, $product);
        // $this->assertEquals('prod123', $product->getId());
    }
}