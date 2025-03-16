<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsDataSlice;
use Mockery;

beforeEach(function () {
    $this->trait = new class() {
        use SupportsDataSlice;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets data slice from integer', function () {
    $this->trait->dataSlice(2);
    expect($this->trait->dataSlice)->toBe(2);
});

it('sets data slice from string', function () {
    $this->trait->dataSlice('2');
    expect($this->trait->dataSlice)->toBe(2);
});

it('sets data slice for all products', function () {
    $this->trait->allProducts();
    expect($this->trait->dataSlice)->toBe(1);
});

it('sets data slice for lowest level products', function () {
    $this->trait->lowestLevelProducts();
    expect($this->trait->dataSlice)->toBe(2);
});

it('builds query with data slice', function () {
    $this->trait->dataSlice = 2;

    $query = $this->trait->buildQuerySupportsDataSlice([]);

    expect($query)->toHaveKey('DataSliceId')
        ->and($query['DataSliceId'])->toBe(2);
});
