<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Objects;

use Performing\FeedBuilder\Contracts\Field;

class Price implements Field
{
    public function __construct(
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
}
