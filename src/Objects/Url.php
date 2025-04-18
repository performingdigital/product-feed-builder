<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Objects;

use Performing\FeedBuilder\Contracts\Field;

/**
 * @property-read string $value A valid URL
 * 
 * When Bag library is installed, this class should be updated to use:
 * #[Required]
 * #[Rule('url')]
 * public readonly string $value
 */
class Url implements Field
{
    public function __construct(
        protected string $value
    ) {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \UnexpectedValueException('Invalid URL');
        }
    }
    
    public function getValue(): string
    {
        return $this->value;
    }
    
    public function toString(): string
    {
        return $this->value;
    }
    
    /**
     * Future implementation with Bag validation
     * This will be activated once Bag library is installed
     */
    public static function rules(): array
    {
        return [
            'value' => ['required', 'url'],
        ];
    }
}
