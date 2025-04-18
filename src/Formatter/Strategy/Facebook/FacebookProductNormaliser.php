<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatter\Strategy\Facebook;

use Performing\FeedBuilder\Formatter\ProductNormaliserInterface;
use Performing\FeedBuilder\Objects\Product;

/**
 * @link https://developers.facebook.com/docs/commerce-platform/catalog/fields#additional-fields
 */
class FacebookProductNormaliser implements ProductNormaliserInterface
{
    public function normalise(Product $product): array
    {
        if (mb_strlen($product->getId()) > 100) {
            throw new \InvalidArgumentException('Product id must be less or equal 100 characters');
        }

        if (mb_strlen($product->getId()) > 150) {
            throw new \InvalidArgumentException('Product title must be less or equal 150 characters');
        }

        if (mb_strlen($product->getDescription()) > 5000) {
            throw new \InvalidArgumentException('Product title must be less or equal 5000 characters');
        }

        $availability = $product->getAvailability();
        if (empty($availability)) {
            throw new \InvalidArgumentException('Unknown availability specified');
        }

        $condition = $product->getCondition();
        if (empty($condition)) {
            throw new \InvalidArgumentException('Unknown condition specified');
        }

        $normalisedProduct = [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'description' => $product->getDescription(),
            'availability' => $availability,
            'condition' => $condition,
            'price' => $product->getPrice()->getAmount() . ' ' . $product->getPrice()->getIso4210Currency(),
            'link' => $product->getLink()->getValue(),
            'image_link' => $product->getImageLink()->getValue(),
            'brand' => $product->getBrand(),
        ];

        return $normalisedProduct;
    }

    public function isSupported(string $marketingPlatform): bool
    {
        return strtolower($marketingPlatform) === 'facebook';
    }
}
