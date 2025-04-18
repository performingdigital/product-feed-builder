<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatters;

use Performing\FeedBuilder\Feed;
use Performing\FeedBuilder\Objects\Product;
use XMLWriter;

/**
 * Formats a feed into a Google Merchant Center XML feed file
 * 
 * @link https://support.google.com/merchants/answer/7052112?hl=en
 */
class GoogleFeedFormatter implements FormatterInterface
{
    private XMLWriter $xml;
    private string $filePath;
    private bool $isFileStarted = false;
    
    /**
     * @param string $filePath The path where the XML feed file will be saved
     */
    public function __construct(string $filePath)
    {   
        $this->filePath = $filePath;
        $this->xml = new XMLWriter();
    }
    
    /**
     * Format the feed and save it to the specified file
     * 
     * @param Feed $feed The feed containing products to be formatted
     * @return void
     */
    public function format(Feed $feed): void
    {
        $this->startFile();
        
        foreach ($feed as $product) {
            $this->appendProduct($product);
        }
        
        $this->endFile();
    }
    
    /**
     * Initialize the XML file with the required headers and root elements
     * 
     * @return void
     */
    private function startFile(): void
    {
        if ($this->isFileStarted) {
            return;
        }
        
        $this->xml->openURI($this->filePath);
        $this->xml->startDocument('1.0', 'UTF-8');
        $this->xml->setIndent(true);
        $this->xml->setIndentString('  ');
        
        // Start the RSS root element with all required namespaces
        $this->xml->startElement('rss');
        $this->xml->writeAttribute('version', '2.0');
        $this->xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        
        // Start the channel element
        $this->xml->startElement('channel');
        
        // Add feed metadata
        $this->xml->writeElement('title', 'Product Feed');
        $this->xml->writeElement('link', 'https://www.example.com');
        $this->xml->writeElement('description', 'Product feed for Google Merchant Center');
        
        $this->isFileStarted = true;
    }
    
    /**
     * Append a product to the XML feed
     * 
     * @param Product $product The product to append to the feed
     * @return void
     */
    private function appendProduct(Product $product): void
    {
        $this->xml->startElement('item');
        
        // Required fields
        $this->writeElement('g:id', $product->getId());
        $this->writeElement('title', $product->getTitle());
        $this->writeElement('description', $product->getDescription());
        $this->writeElement('link', $product->getLink()->toString());
        $this->writeElement('g:image_link', $product->getImageLink()->toString());
        $this->writeElement('g:availability', $product->getAvailability()->toString());
        $this->writeElement('g:price', $product->getPrice()->toString());
        $this->writeElement('g:brand', $product->getBrand());
        $this->writeElement('g:condition', $product->getCondition()->toString());
        
        // Optional fields
        if ($product->getGoogleProductCategory()) {
            $this->writeElement('g:google_product_category', (string)$product->getGoogleProductCategory());
        }
        
        if ($product->getInventory() !== null) {
            $this->writeElement('g:inventory', (string)$product->getInventory());
        }
        
        if ($product->getSalePrice()) {
            $this->writeElement('g:sale_price', $product->getSalePrice()->toString());
            
            if ($product->getSalePriceEffectiveDate()) {
                $this->writeElement('g:sale_price_effective_date', $product->getSalePriceEffectiveDate()->toString());
            }
        }
        
        $this->xml->endElement(); // item
        $this->xml->flush(); // Write to file immediately to save memory
    }
    
    /**
     * Write an element with proper escaping
     * 
     * @param string $name The element name
     * @param string $value The element value
     * @return void
     */
    private function writeElement(string $name, string $value): void
    {
        $this->xml->startElement($name);
        $this->xml->text($value); // This properly escapes the content
        $this->xml->endElement();
    }
    
    /**
     * Finalize the XML file by closing all open elements
     * 
     * @return void
     */
    private function endFile(): void
    {
        if (!$this->isFileStarted) {
            return;
        }
        
        $this->xml->endElement(); // channel
        $this->xml->endElement(); // rss
        $this->xml->endDocument();
        
        $this->isFileStarted = false;
    }
    
    /**
     * Ensure the file is properly closed if the object is destroyed
     */
    public function __destruct()
    {
        if ($this->isFileStarted) {
            $this->endFile();
        }
    }
}