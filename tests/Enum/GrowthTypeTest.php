<?php

use Bernskiold\LaravelEuromonitor\Enum\GrowthType;

it('has all expected cases', function () {
    expect(GrowthType::cases())->toHaveCount(2)
        ->and(GrowthType::None->value)->toBe('None')
        ->and(GrowthType::YearOnYear->value)->toBe('YearOnYear');
});

it('maps cases to correct codes', function () {
    expect(GrowthType::None->code())->toBe(0)
        ->and(GrowthType::YearOnYear->code())->toBe(1);
});
