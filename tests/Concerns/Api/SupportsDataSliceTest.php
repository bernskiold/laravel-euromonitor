<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsDataSlice;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource {
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

it('builds query with the statistics data slice key', function () {
    $this->trait->dataSlice = 2;

    $query = $this->trait->buildQuerySupportsDataSlice([]);

    expect($query)->toHaveKey('DataSliceID')
        ->and($query['DataSliceID'])->toBe(2);
});

it('builds query with the mass extraction data slice key', function () {
    $this->trait->usesMassExtraction = true;
    $this->trait->dataSlice = 2;

    $query = $this->trait->buildQuerySupportsDataSlice([]);

    expect($query)->toHaveKey('DataSliceId')
        ->and($query['DataSliceId'])->toBe(2);
});
