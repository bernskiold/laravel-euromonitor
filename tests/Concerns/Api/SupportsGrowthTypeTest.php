<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsGrowthType;
use Bernskiold\LaravelEuromonitor\Enum\GrowthType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource {
        use SupportsGrowthType;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets growth type', function () {
    $this->trait->growthType(GrowthType::YearOnYear);
    expect($this->trait->growthType)->toBe(GrowthType::YearOnYear);
});

it('sets year on year growth type', function () {
    $this->trait->yearOnYearGrowth();
    expect($this->trait->growthType)->toBe(GrowthType::YearOnYear);
});


it('builds query with valid growthType', function () {
    $this->trait->growthType = GrowthType::YearOnYear;

    $query = $this->trait->buildQuerySupportsGrowthType([]);

    expect($query)->toHaveKey('growthType')
        ->and($query['growthType'])->toBe(GrowthType::YearOnYear->value);
});
