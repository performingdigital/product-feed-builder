<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Traits;

/**
 * Trait for objects that need validation.
 * This is a placeholder for future implementation with the Bag library.
 * 
 * When Bag is installed, this trait will be updated to use:
 * use Bag\Bag;
 */
trait ValidatedObject
{
    /**
     * Create a new instance of the class with validation
     * Placeholder for future implementation with Bag library
     *
     * @param array $data Data to populate the object with
     * @return static
     */
    public static function create(array $data): static
    {
        throw new \LogicException(
            'This method requires the Bag library to be installed. ' . 
            'Run "composer install" to add it to your project.'
        );
    }
    
    /**
     * Create a new instance of the class without validation
     * Placeholder for future implementation with Bag library
     *
     * @param array $data Data to populate the object with
     * @return static
     */
    public static function createWithoutValidation(array $data): static
    {
        throw new \LogicException(
            'This method requires the Bag library to be installed. ' . 
            'Run "composer install" to add it to your project.'
        );
    }
}