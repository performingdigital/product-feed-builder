<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Objects;

use Performing\FeedBuilder\Objects\Availability;
use Performing\FeedBuilder\Objects\Condition;
use Performing\FeedBuilder\Objects\DateTimeRange;
use Performing\FeedBuilder\Objects\Price;
use Performing\FeedBuilder\Objects\Url;

/**
 * Element of feed
 */
class Product
{
    /**
     * Optional. Required if sale.
     * Discounted price and currency of the item, if the item is on sale.
     */
    private ?Price $salePrice;

    private string|int|null $facebookProductCategory;

    private string|int|null $googleProductCategory;

    private ?string $internalProductCategory;

    private ?int $inventory;

    private ?DateTimeRange $salePriceEffectiveDate;

    public function __construct(
        /** Unique ID for item. Can be a variant for a product. Use the SKU if you can. For Facebook must be less or equal 100 chars */
        private string $id,
        /** A specific, relevant title for the item. For Facebook must be less or equal 150 chars */
        private string $title,
        /** A short, relevant description of the item. Include specific or unique product features, such as like material or color. For Facebook must be less or equal 5000 chars */
        private string $description,
        /** Current availability of the item in your store */
        private Availability $availability,
        /** Condition of the item in your store */
        private Condition $condition,
        /** Current price of the item */
        private Price $price,
        /** URL of the specific product page where people can buy the item */
        private Url $link,
        /** URL for the main image of your item */
        private Url $imageLink,
        /** Brand name, unique manufacturer part number (MPN), or Global Trade Item Number (GTIN) of the item */
        private string $brand,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAvailability(): Availability
    {
        return $this->availability;
    }

    public function getCondition(): Condition
    {
        return $this->condition;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getLink(): Url
    {
        return $this->link;
    }

    public function getImageLink(): Url
    {
        return $this->imageLink;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getFacebookProductCategory(): string|int|null
    {
        return $this->facebookProductCategory;
    }

    /**
     * Optional, may be useful only for Facebook feed
     *
     * @link https://developers.facebook.com/docs/commerce-platform/catalog/categories/#fb-prod-cat
     *
     * The Facebook product category represents the item according to the Facebook product taxonomy.
     * This taxonomy organizes products for sale into categories and subcategories.
     * For example, Health & Beauty > Beauty > Makeup > Eye Makeup > Mascara.
     *
     * To provide a Facebook product category for your items, add the fb_product_category field in your data feed file.
     * In this field, enter a supported category from the list below. Facebook product categories are available in
     * multiple languages.
     *
     * List of categories in your language below:
     * @link https://www.facebook.com/products/categories/en_US.txt
     * @link https://www.facebook.com/products/categories/en_US
     *
     * For each category, you can provide either the taxonomy path (such as Health & Beauty > Beauty > Makeup > Eye Makeup > Mascara) or the category ID number (such as 281). Category names are not case sensitive.
     * When you provide a Facebook product category, you can also use additional fields specific to that category to provide more detailed information about your items.
     *
     * @param int|string $facebookProductCategory
     */
    public function setFacebookProductCategory($facebookProductCategory): void
    {
        if ($facebookProductCategory || !is_int($facebookProductCategory) || !is_string($facebookProductCategory)) {
            throw new \InvalidArgumentException('Facebook product category must me taxonomy path or int');
        }

        $this->facebookProductCategory = $facebookProductCategory;
    }

    /**
     * @return int|string|null
     */
    public function getGoogleProductCategory()
    {
        return $this->googleProductCategory;
    }

    /**
     * Optional, may be used both for Facebook and Google product feeds.
     *
     * For Facebook:
     *      Optional for Instagram Shopping and Page Shops, but required to enable onsite checkout on these
     *      channels (U.S. only). Required for Marketplace (U.S. only).
     *
     *      The Google product category (GPC) (google_product_category) represents the item according to the
     *      Google's product taxonomy. Use the category's taxonomy path or its category ID number:
     *      @link https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt
     *
     * For Google:
     *      ...
     *
     * Example: Apparel & Accessories > Clothing > Shirts & Tops or 212
     *
     * @param int|string $googleProductCategory
     */
    public function setGoogleProductCategory($googleProductCategory): void
    {
        if ($googleProductCategory || !is_int($googleProductCategory) || !is_string($googleProductCategory)) {
            throw new \InvalidArgumentException('Google product category must me taxonomy path or int');
        }

        $this->googleProductCategory = $googleProductCategory;
    }

    /**
     * @return string|null
     */
    public function getInternalProductCategory(): ?string
    {
        return $this->internalProductCategory;
    }

    /**
     * Category the item belongs to, according to your business's product categorization system, if you have one.
     * You can also enter a Google product category. For commerce, represents the product category in your
     *
     * @link https://developers.facebook.com/docs/marketing-api/catalog/guides/product-categories
     *
     * Example: Home & Garden > Kitchen & Dining > Appliances > Refrigerators
     *
     * @param string|null $internalProductCategory
     */
    public function setInternalProductCategory(?string $internalProductCategory): void
    {
        $this->internalProductCategory = $internalProductCategory;
    }

    public function getInventory(): ?int
    {
        return $this->inventory;
    }

    /**
     * Optional.
     *
     * Quantity of an item in your inventory. People can't buy this item unless the inventory is 1 or higher.
     *
     * For Facebook:
     *      Required for Instagram Shopping with checkout, Page Shops, and Marketplace.
     *      Optional for Instagram Shopping with product tagging only.
     *
     * @param int $inventory
     */
    public function setInventory(int $inventory): void
    {
        if ($inventory < 1) {
            throw new \OutOfBoundsException('Must be positive');
        }

        $this->inventory = $inventory;
    }

    public function getSalePrice(): ?Price
    {
        return $this->salePrice;
    }

    /**
     * Optional. Required if sale.
     * Discounted price and currency of the item, if the item is on sale.
     */
    public function setSalePrice(Price $salePrice): void
    {
        $this->salePrice = $salePrice;
    }

    public function getSalePriceEffectiveDate(): ?DateTimeRange
    {
        return $this->salePriceEffectiveDate;
    }

    /**
     * Optional. Required if checkout.
     *
     * Time range for your sale period, including the date, time, and time zone when your sale starts and ends.
     * If you don't enter sale dates, any items with a {@see $salePrice} remains on sale until
     * you remove their sale price.
     */
    public function setSalePriceEffectiveDate(?DateTimeRange $salePriceEffectiveDate): void
    {
        $this->salePriceEffectiveDate = $salePriceEffectiveDate;
    }
}
