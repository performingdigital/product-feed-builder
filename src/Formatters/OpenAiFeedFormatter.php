<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatters;

use Performing\FeedBuilder\Feed;
use Performing\FeedBuilder\Objects\Product;
use Performing\FeedBuilder\Writers\CsvMemoryWriter;
use Performing\FeedBuilder\Writers\CsvWriterInterface;

/**
 * Formats a feed into an OpenAI product feed CSV file, used by ChatGPT Ads
 *
 * @link https://developers.openai.com/commerce/specs/feed
 * @link https://developers.openai.com/ads/product-feeds
 */
class OpenAiFeedFormatter implements FormatterInterface
{
    /**
     * @param string $sellerName Name of the seller, the same for every product
     * @param string $country Country of the store and of the ads, alpha 2 by ISO 3166-1 (e.g. "IT")
     */
    public function __construct(
        protected string $sellerName,
        protected string $country,
        protected CsvWriterInterface $writer = new CsvMemoryWriter(),
    ) {}

    /**
     * Format the feed and save it with the writer
     */
    public function format(Feed $feed): ?string
    {
        foreach ($feed as $product) {
            $this->append($product);
        }

        return $this->writer->save();
    }

    /**
     * Append a product to the CSV feed
     */
    private function append(Product $product): void
    {
        $this->writer->writeRow([
            // Required fields
            'item_id' => $product->getId(),
            'title' => $product->getTitle(),
            'description' => $product->getDescription(),
            'url' => $product->getLink()->toString(),
            'brand' => $product->getBrand(),
            'image_url' => $product->getImageLink()->toString(),
            'availability' => str_replace(' ', '_', $product->getAvailability()->toString()),
            'price' => $product->getPrice()->toString(),
            'seller_name' => $this->sellerName,
            'target_countries' => $this->country,
            'store_country' => $this->country,
            'condition' => $product->getCondition()->toString(),
            'is_ads_eligible' => 'true',

            // Optional fields
            'sale_price' => $product->getSalePrice()?->toString() ?? '',
            'ads_metadata' => $product->getCustomFields() ? json_encode($product->getCustomFields(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '',
        ]);
    }
}
