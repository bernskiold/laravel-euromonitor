<?php

use Bernskiold\LaravelEuromonitor\Enum\ExchangeRate;

it('has all expected cases', function () {
    expect(ExchangeRate::cases())->toHaveCount(3)
        ->and(ExchangeRate::Fixed->value)->toBe('Fixed')
        ->and(ExchangeRate::YearOnYear->value)->toBe('YearOnYear')
        ->and(ExchangeRate::Both->value)->toBe('Both');
});

it('maps cases to correct codes', function () {
    expect(ExchangeRate::Both->code())->toBe(0)
        ->and(ExchangeRate::Fixed->code())->toBe(1)
        ->and(ExchangeRate::YearOnYear->code())->toBe(2);
});
