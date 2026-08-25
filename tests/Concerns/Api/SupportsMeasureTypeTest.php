<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsMeasureType;
use Bernskiold\LaravelEuromonitor\Enum\MeasureType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource {
        use SupportsMeasureType;
    };
});

afterEach(function () {
    Mockery::close();
});

it('can set measure type via enum', function () {
    $result = $this->trait->measureType(MeasureType::BrandShare);

    expect($result)->toBe($this->trait)
        ->and($this->trait->measureType)->toBe(MeasureType::BrandShare);
});

it('can set measure type via string', function () {
    $result = $this->trait->measureType('BrandShare');

    expect($result)->toBe($this->trait)
        ->and($this->trait->measureType)->toBe(MeasureType::BrandShare);
});

it('can set brand share measure type', function () {
    $result = $this->trait->brandShare();

    expect($result)->toBe($this->trait)
        ->and($this->trait->measureType)->toBe(MeasureType::BrandShare);
});

it('can set company share measure type', function () {
    $result = $this->trait->companyShare();

    expect($result)->toBe($this->trait)
        ->and($this->trait->measureType)->toBe(MeasureType::CompanyShare);
});

it('can set market size measure type', function () {
    $result = $this->trait->marketSize();

    expect($result)->toBe($this->trait)
        ->and($this->trait->measureType)->toBe(MeasureType::MarketSize);
});
