<?php

use Bernskiold\LaravelEuromonitor\Enum\MeasureType;

it('has all expected cases', function () {
    expect(MeasureType::cases())->toHaveCount(3)
        ->and(MeasureType::CompanyShare->value)->toBe('CompanyShare')
        ->and(MeasureType::MarketSize->value)->toBe('MarketSize')
        ->and(MeasureType::BrandShare->value)->toBe('BrandShare');
});
