<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatters;

use Performing\FeedBuilder\Feed;
use Performing\FeedBuilder\Objects\Product;

/**
 * Formats a feed into a Facebook product feed CSV file
 * 
 * @link https://developers.facebook.com/docs/marketing-api/catalog/reference#feed-format
 */
class FacebookFeedFormatter implements FormatterInterface
{
    private string $filePath;
    private $fileHandle;
    private bool $isFileStarted = false;
    
    /**
     * @param string $filePath The path where the CSV feed file will be saved
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
    
    /**
     * Format the feed and save it to the specified file
     * 
     * @param Feed $feed The feed containing products to be formatted
     * @return void
     */
    public function format(Feed $feed): void
    {
        $this->startFile($feed);
        
        foreach ($feed as $product) {
            $this->appendProduct($product);
        }
        
        $this->endFile();
    }
    
    /**
     * Initialize the CSV file with headers
     * 
     * @param Feed $feed The feed containing products
     * @return void
     */
    private function startFile(Feed $feed): void
    {
        if ($this->isFileStarted) {
            return;
        }
        
        $this->fileHandle = fopen($this->filePath, 'w');
        
        if ($this->fileHandle === false) {
            throw new \RuntimeException("Could not open file: {$this->filePath}");
        }
        
        // Get the first product to determine the headers
        $feed->rewind();
        $firstProduct = $feed->current();
        
        if ($firstProduct) {
            fputcsv($this->fileHandle, $this->getHeaders($firstProduct));
        }
        
        $this->isFileStarted = true;
        $feed->rewind(); // Reset the iterator
    }
    
    /**
     * Generate the CSV headers based on the first product
     * 
     * @param Product $product The product to extract headers from
     * @return array The CSV headers
     */
    private function getHeaders(Product $product): array
    {
        return [
            'id',
            'title',
            'description',
            'availability',
            'condition',
            'price',
            'link',
            'image_link',
            'brand',
            'google_product_category',
            'fb_product_category',
            'inventory',
            'sale_price',
            'sale_price_effective_date'
        ];
    }
    
    /**
     * Append a product to the CSV feed
     * 
     * @param Product $product The product to append to the feed
     * @return void
     */
    private function appendProduct(Product $product): void
    {
        $row = [
            $product->getId(),
            $product->getTitle(),
            $product->getDescription(),
            $product->getAvailability()->toString(),
            $product->getCondition()->toString(),
            $product->getPrice()->toString(),
            $product->getLink()->toString(),
            $product->getImageLink()->toString(),
            $product->getBrand(),
            $product->getGoogleProductCategory() ? (string)$product->getGoogleProductCategory() : '',
            $product->getFacebookProductCategory() ? (string)$product->getFacebookProductCategory() : '',
            $product->getInventory() !== null ? (string)$product->getInventory() : '',
            $product->getSalePrice() ? $product->getSalePrice()->toString() : '',
            $product->getSalePriceEffectiveDate() ? $product->getSalePriceEffectiveDate()->toString() : ''
        ];
        
        fputcsv($this->fileHandle, $row);
    }
    
    /**
     * Finalize the CSV file by closing the file handle
     * 
     * @return void
     */
    private function endFile(): void
    {
        if (!$this->isFileStarted || $this->fileHandle === null) {
            return;
        }
        
        fclose($this->fileHandle);
        $this->fileHandle = null;
        $this->isFileStarted = false;
    }
    
    /**
     * Ensure the file is properly closed if the object is destroyed
     */
    public function __destruct()
    {
        $this->endFile();
    }
}