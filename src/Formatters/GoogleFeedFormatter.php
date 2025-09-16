<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatters;

use Performing\FeedBuilder\Feed;
use Performing\FeedBuilder\Objects\Product;
use Performing\FeedBuilder\Writers\MemoryWriter;
use Performing\FeedBuilder\Writers\Writer;
use XMLWriter;

/**
 * Formats a feed into a Google Merchant Center XML feed file
 *
 * @link https://support.google.com/merchants/answer/7052112?hl=en
 */
class GoogleFeedFormatter implements FormatterInterface
{
    public function __construct(
        protected Writer $writer = new MemoryWriter(),
    ) {}

    /**
     * Format the feed and save it to the specified file
     */
    public function format(Feed $feed): ?string
    {
        foreach ($feed as $product) {
            $this->append($product);
        }

        return $this->writer->save();
    }

    /**
     * Append a product to the XML feed
     *
     * @param Product $product The product to append to the feed
     * @return void
     */
    private function append(Product $product): void
    {
        $this->writer->startElement('item');

        // Required fields
        $this->writer->writeElement('g:id', $product->getId());
        $this->writer->writeElement('title', $product->getTitle());
        $this->writer->writeElement('description', $product->getDescription());
        $this->writer->writeElement('link', $product->getLink()->toString());
        $this->writer->writeElement('g:image_link', $product->getImageLink()->toString());
        $this->writer->writeElement('g:availability', $product->getAvailability()->toString());
        $this->writer->writeElement('g:price', $product->getPrice()->toString());
        $this->writer->writeElement('g:brand', $product->getBrand());
        $this->writer->writeElement('g:condition', $product->getCondition()->toString());


        foreach ($product->getCustomFields() as $key => $value) {
            $this->writer->writeElement($key, $value);
        }

        // Optional fields
        if ($product->getGoogleProductCategory()) {
            $this->writer->writeElement('g:google_product_category', (string) $product->getGoogleProductCategory());
        }

        if ($product->getInventory() !== null) {
            $this->writer->writeElement('g:inventory', (string) $product->getInventory());
        }

        if ($product->getSalePrice()) {
            $this->writer->writeElement('g:sale_price', $product->getSalePrice()->toString());

            if ($product->getSalePriceEffectiveDate()) {
                $this->writer->writeElement('g:sale_price_effective_date', $product->getSalePriceEffectiveDate()->toString());
            }
        }

        $this->writer->endElement(); // item
    }
}
