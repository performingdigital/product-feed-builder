<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Objects;

use Performing\FeedBuilder\Contracts\Field;

/**
 * @property-read string $amount Amount with "." as decimal point
 * @property-read string $iso4210Currency Currency code by ISO 4210
 * 
 * When Bag library is installed, this class should be updated to use:
 * #[Required]
 * #[Regex('/^\d+(\.\d{2})?$/')]
 * public readonly string $amount
 * 
 * #[Required]
 * #[Regex('/[A-Z]{3}/')]
 * public readonly string $iso4210Currency
 */
class Price implements Field
{
    public function __construct(
        // Using property promotion
        protected string $amount,
        /** @see https://en.wikipedia.org/wiki/ISO_4217 */
        protected string $iso4210Currency,
    ) {
        if (!preg_match('/^\d+(\.\d{2})?$/', $amount)) {
            throw new \InvalidArgumentException('Amount must be number with "." as decimal point');
        }
        if (!preg_match('/[A-Z]{3}/', $iso4210Currency)) {
            throw new \InvalidArgumentException('Currency must be alpha 3 by ISO 4210');
        }
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getIso4210Currency(): string
    {
        return $this->iso4210Currency;
    }

    public function toString(): string
    {
        return "{$this->getAmount()} {$this->getIso4210Currency()}";
    }
    
    /**
     * Future implementation with Bag validation
     * This will be activated once Bag library is installed
     */
    public static function rules(): array
    {
        return [
            'amount' => ['required', 'regex:/^\d+(\.\d{2})?$/'],
            'iso4210Currency' => ['required', 'regex:/[A-Z]{3}/'],
        ];
    }
}
