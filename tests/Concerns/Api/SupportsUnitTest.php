<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsUnit;
use Bernskiold\LaravelEuromonitor\Enum\UnitType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class() {
        use SupportsUnit;
    };
});

afterEach(function () {
    Mockery::close();
});

it('can set unit via enum', function () {
    $result = $this->trait->unit(UnitType::Percentage);

    expect($result)->toBe($this->trait)
        ->and($this->trait->unit)->toBe(UnitType::Percentage);
});

it('can set unit via string', function () {
    $result = $this->trait->unit('Percentage');

    expect($result)->toBe($this->trait)
        ->and($this->trait->unit)->toBe(UnitType::Percentage);
});

it('can set percentages unit', function () {
    $result = $this->trait->percentages();

    expect($result)->toBe($this->trait)
        ->and($this->trait->unit)->toBe(UnitType::Percentage);
});

it('can set actual unit', function () {
    $result = $this->trait->actual();

    expect($result)->toBe($this->trait)
        ->and($this->trait->unit)->toBe(UnitType::Actual);
});

it('can set ranking unit', function () {
    $result = $this->trait->ranking();

    expect($result)->toBe($this->trait)
        ->and($this->trait->unit)->toBe(UnitType::Ranking);
});
