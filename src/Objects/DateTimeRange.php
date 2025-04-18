<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Objects;

use DateTimeImmutable;
use Performing\FeedBuilder\Contracts\Field;

class DateTimeRange implements Field
{
    public function __construct(
        private DateTimeImmutable $dateTimeFrom,
        private DateTimeImmutable $dateTimeTo,
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
}
