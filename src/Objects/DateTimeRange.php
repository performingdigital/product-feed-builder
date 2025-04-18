<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Objects;

use DateTimeImmutable;
use Performing\FeedBuilder\Contracts\Field;

/**
 * @property-read DateTimeImmutable $dateTimeFrom Start date/time
 * @property-read DateTimeImmutable $dateTimeTo End date/time
 * 
 * When Bag library is installed, this class should be updated to use:
 * #[Required]
 * public readonly DateTimeImmutable $dateTimeFrom
 * 
 * #[Required]
 * public readonly DateTimeImmutable $dateTimeTo
 */
class DateTimeRange implements Field
{
    public function __construct(
        protected DateTimeImmutable $dateTimeFrom,
        protected DateTimeImmutable $dateTimeTo,
    ) {
        if ($dateTimeFrom >= $dateTimeTo) {
            throw new \LogicException('From date must be greater than to date');
        }
    }

    public function getDateTimeFrom(): DateTimeImmutable
    {
        return $this->dateTimeFrom;
    }

    public function getDateTimeTo(): DateTimeImmutable
    {
        return $this->dateTimeTo;
    }

    public function toString(): string
    {
        return $this->dateTimeFrom->format('Y-m-d\TH:i:s') . '-' . $this->dateTimeTo->format('Y-m-d\TH:i:s');
    }
    
    /**
     * Future implementation with Bag validation
     * This will be activated once Bag library is installed
     */
    public static function rules(): array
    {
        return [
            'dateTimeFrom' => ['required', 'date'],
            'dateTimeTo' => ['required', 'date', 'after:dateTimeFrom'],
        ];
    }
}
