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
 *
 * When Bag library is installed, this class should be updated to use:
 * extends Bag
 *
 * #[Required]
 * #[Str]
 * public readonly string $id
 *
 * #[Required]
 * #[Str]
 * public readonly string $title
 *
 * etc.
 */
class Product
{
    /**
     * Optional. Required if sale.
     * Discounted price and currency of the item, if the item is on sale.
     */
    private ?Price $salePrice = null;

    /**
     * Optional, may be useful only for Facebook feed
     * The Facebook product category represents the item according to the Facebook product taxonomy.
     */
    private string|int|null $facebookProductCategory = null;

    /**
     * Optional, may be used both for Facebook and Google product feeds.
     * The Google product category (GPC) represents the item according to the Google's product taxonomy.
     */
    private string|int|null $googleProductCategory = null;

    /**
     * Category the item belongs to, according to your business's product categorization system.
     */
    private ?string $internalProductCategory = null;

    /**
     * Optional. Quantity of an item in your inventory.
     * People can't buy this item unless the inventory is 1 or higher.
     */
    private ?int $inventory = null;

    /**
     * Optional. Required if checkout.
     * Time range for your sale period, including the date, time, and time zone when your sale starts and ends.
     */
    private ?DateTimeRange $salePriceEffectiveDate = null;

    protected array $customFields = [];

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

        ?Price $salePrice = null,
        string|int|null $facebookProductCategory = null,
        string|int|null $googleProductCategory = null,
        ?string $internalProductCategory = null,
        ?int $inventory = null,
        ?DateTimeRange $salePriceEffectiveDate = null
    ) {
        $this->salePrice = $salePrice;
        $this->facebookProductCategory = $facebookProductCategory;
        $this->googleProductCategory = $googleProductCategory;
        $this->internalProductCategory = $internalProductCategory;

        // Validate inventory is positive
        if ($inventory !== null && $inventory < 1) {
            throw new \OutOfBoundsException('Inventory must be positive');
        }
        $this->inventory = $inventory;

        $this->salePriceEffectiveDate = $salePriceEffectiveDate;
    }

    public function additional(array $customFields): self
    {
        $this->customFields = $customFields;

        return $this;
    }

    public function getCustomFields(): array
    {
        return $this->customFields;
    }

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

    public function setFacebookProductCategory(string|int|null $facebookProductCategory): void
    {
        $this->facebookProductCategory = $facebookProductCategory;
    }

    public function getGoogleProductCategory(): string|int|null
    {
        return $this->googleProductCategory;
    }

    public function setGoogleProductCategory(string|int|null $googleProductCategory): void
    {
        $this->googleProductCategory = $googleProductCategory;
    }

    public function getInternalProductCategory(): ?string
    {
        return $this->internalProductCategory;
    }

    public function setInternalProductCategory(?string $internalProductCategory): void
    {
        $this->internalProductCategory = $internalProductCategory;
    }

    public function getInventory(): ?int
    {
        return $this->inventory;
    }

    public function setInventory(?int $inventory): void
    {
        if ($inventory !== null && $inventory < 1) {
            throw new \OutOfBoundsException('Inventory must be positive');
        }

        $this->inventory = $inventory;
    }

    public function getSalePrice(): ?Price
    {
        return $this->salePrice;
    }

    public function setSalePrice(?Price $salePrice): void
    {
        $this->salePrice = $salePrice;
    }

    public function getSalePriceEffectiveDate(): ?DateTimeRange
    {
        return $this->salePriceEffectiveDate;
    }

    public function setSalePriceEffectiveDate(?DateTimeRange $salePriceEffectiveDate): void
    {
        $this->salePriceEffectiveDate = $salePriceEffectiveDate;
    }

    /**
     * Future implementation with Bag validation
     * This will be activated once Bag library is installed
     */
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:5000'],
            'availability' => ['required'],
            'condition' => ['required'],
            'price' => ['required'],
            'link' => ['required'],
            'imageLink' => ['required'],
            'brand' => ['required', 'string'],
            'inventory' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
