<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Examples;

use Performing\FeedBuilder\Feed;
use Performing\FeedBuilder\Formatters\FormatterFactory;
use Performing\FeedBuilder\Objects\Product;
use Performing\FeedBuilder\Objects\Price;
use Performing\FeedBuilder\Objects\Url;
use Performing\FeedBuilder\Objects\Availability;
use Performing\FeedBuilder\Objects\Condition;

/**
 * Example showing how to use the feed formatters
 */
class FormatterExample
{
    /**
     * Create a sample feed with products
     * 
     * @param int $productCount Number of products to create
     * @return Feed
     */
    public static function createSampleFeed(int $productCount = 10): Feed
    {
        $products = [];
        
        for ($i = 1; $i <= $productCount; $i++) {
            $products[] = new Product(
                "product-{$i}",
                "Product {$i}",
                "This is a description for product {$i}",
                Availability::InStock,
                Condition::New,
                new Price("19.99", "USD"),
                new Url("https://example.com/products/{$i}"),
                new Url("https://example.com/images/{$i}.jpg"),
                "Sample Brand"
            );
        }
        
        return new Feed($products);
    }
    
    /**
     * Generate a Google feed file
     * 
     * @param string $outputPath The path where the XML feed file will be saved
     * @param int $productCount Number of products to include in the feed
     */
    public static function generateGoogleFeed(string $outputPath, int $productCount = 100): void
    {
        $feed = self::createSampleFeed($productCount);
        $formatter = FormatterFactory::createGoogleFormatter($outputPath);
        $formatter->format($feed);
        
        echo "Google feed created at: {$outputPath}\n";
        echo "Total products: {$productCount}\n";
    }
    
    /**
     * Generate a Facebook feed file
     * 
     * @param string $outputPath The path where the CSV feed file will be saved
     * @param int $productCount Number of products to include in the feed
     */
    public static function generateFacebookFeed(string $outputPath, int $productCount = 100): void
    {
        $feed = self::createSampleFeed($productCount);
        $formatter = FormatterFactory::createFacebookFormatter($outputPath);
        $formatter->format($feed);
        
        echo "Facebook feed created at: {$outputPath}\n";
        echo "Total products: {$productCount}\n";
    }
}