<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Formatter\Strategy\Facebook;

use Performing\FeedBuilder\Feed;
use Performing\FeedBuilder\Objects\Availability;
use Performing\FeedBuilder\Objects\Condition;
use Performing\FeedBuilder\Objects\Price;
use Performing\FeedBuilder\Objects\Product;
use Performing\FeedBuilder\Objects\Url;
use PHPUnit\Framework\TestCase;

class FacebookFeedCsvEncoderTest extends TestCase
{
    public function testEncode()
    {
        $product = new Product(
            id: 'sku',
            title: 'title',
            description: 'description',
            availability: Availability::InStock,
            condition: Condition::New,
            price: new Price('42.42', 'UAH'),
            link: new Url('https://example.com/item'),
            imageLink: new Url('https://example.com/item.png'),
            brand: 'SomeBrand',
        );

        $feed = new Feed([$product]);

        $encoder = new FacebookFeedCsvEncoder();
        $normaliser = new FacebookProductNormaliser();

        $actualOutput = iterator_to_array($encoder->encode($feed, $normaliser));

        $expectedOutput = [
            'id,title,description,availability,condition,price,link,image_link,brand',
            'sku,title,description,"in stock",new,"42.42 UAH",https://example.com/item,https://example.com/item.png,SomeBrand',
        ];

        $this->assertSame(
            $expectedOutput,
            $actualOutput,
        );
    }
}
