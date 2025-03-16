<?php

use Bernskiold\LaravelEuromonitor\Enum\InflationType;

it('has all expected cases', function () {
    expect(InflationType::cases())->toHaveCount(3)
        ->and(InflationType::Current->value)->toBe('Current')
        ->and(InflationType::Constant->value)->toBe('Constant')
        ->and(InflationType::Both->value)->toBe('Both');
});

it('maps cases to correct codes', function () {
    expect(InflationType::Both->code())->toBe(0)
        ->and(InflationType::Current->code())->toBe(3)
        ->and(InflationType::Constant->code())->toBe(5);
});
