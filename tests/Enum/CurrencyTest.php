<?php

use Bernskiold\LaravelEuromonitor\Enum\Currency;

it('has all expected cases', function () {
    expect(Currency::cases())->toHaveCount(6)
        ->and(Currency::None->value)->toBe('None')
        ->and(Currency::USD->value)->toBe('USD')
        ->and(Currency::EUR->value)->toBe('EUR')
        ->and(Currency::GBP->value)->toBe('GBP')
        ->and(Currency::JPY->value)->toBe('JPY')
        ->and(Currency::CHF->value)->toBe('CHF');
});

it('maps cases to correct codes', function () {
    expect(Currency::None->code())->toBe(0)
        ->and(Currency::USD->code())->toBe(1)
        ->and(Currency::EUR->code())->toBe(2)
        ->and(Currency::GBP->code())->toBe(3)
        ->and(Currency::JPY->code())->toBe(4)
        ->and(Currency::CHF->code())->toBe(5);
});
