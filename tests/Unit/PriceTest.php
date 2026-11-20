<?php

declare(strict_types=1);

use Performing\FeedBuilder\Objects\Price;

it('constructs price with correct amount and currency', function (string $amount, string $currency) {
    $price = new Price($amount, $currency);

    expect($price->getAmount())->toBe($amount);
    expect($price->getIso4210Currency())->toBe($currency);
})->with([
    ['1.01', 'USD'],
    ['12.01', 'EUR'],
    ['123.01', 'UAH'],
    ['1234.01', 'UAH'],
]);

it('writes the price with two decimals and the currency', function (string $amount, string $expected) {
    expect((new Price($amount, 'EUR'))->toString())->toBe($expected);
})->with([
    ['12500', '12500.00 EUR'],
    ['19.99', '19.99 EUR'],
    ['0.50', '0.50 EUR'],
]);
