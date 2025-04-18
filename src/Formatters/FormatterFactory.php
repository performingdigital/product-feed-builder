<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatters;

/**
 * Factory for creating feed formatters
 */
class FormatterFactory
{
    /**
     * Create a Google feed formatter
     * 
     * @param string $outputPath The path where the XML feed file will be saved
     * @return GoogleFeedFormatter
     */
    public static function createGoogleFormatter(string $outputPath): GoogleFeedFormatter
    {
        return new GoogleFeedFormatter($outputPath);
    }
    
    /**
     * Create a Facebook feed formatter
     * 
     * @param string $outputPath The path where the CSV feed file will be saved
     * @return FacebookFeedFormatter
     */
    public static function createFacebookFormatter(string $outputPath): FacebookFeedFormatter
    {
        return new FacebookFeedFormatter($outputPath);
    }
    
    /**
     * Create a formatter based on the platform type
     * 
     * @param string $platform The platform (google or facebook)
     * @param string $outputPath The path where the feed file will be saved
     * @return FormatterInterface
     * @throws \InvalidArgumentException If the platform is not supported
     */
    public static function createFormatter(string $platform, string $outputPath): FormatterInterface
    {
        switch (strtolower($platform)) {
            case 'google':
                return self::createGoogleFormatter($outputPath);
            case 'facebook':
                return self::createFacebookFormatter($outputPath);
            default:
                throw new \InvalidArgumentException("Unsupported platform: {$platform}");
        }
    }
}