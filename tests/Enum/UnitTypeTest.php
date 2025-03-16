<?php

use Bernskiold\LaravelEuromonitor\Enum\UnitType;

it('has all expected cases', function () {
    expect(UnitType::cases())->toHaveCount(3)
        ->and(UnitType::Percentage->value)->toBe('Percentage')
        ->and(UnitType::Actual->value)->toBe('Actual')
        ->and(UnitType::Ranking->value)->toBe('Ranking');
});

it('maps cases to correct codes', function () {
    expect(UnitType::Percentage->code())->toBe(0)
        ->and(UnitType::Actual->code())->toBe(2)
        ->and(UnitType::Ranking->code())->toBe(1);
});
