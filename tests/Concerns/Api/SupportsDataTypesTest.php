<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsDataTypes;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class() {
        use SupportsDataTypes;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets data types from array', function () {
    $this->trait->dataTypes([1, 2, 3]);
    expect($this->trait->dataTypes)->toBe([1, 2, 3]);
});

it('sets data types from single value', function () {
    $this->trait->dataTypes(1);
    expect($this->trait->dataTypes)->toBe([1]);
});

it('sets data types from string', function () {
    $this->trait->dataTypes('1');
    expect($this->trait->dataTypes)->toBe(['1']);
});

it('builds query with data types', function () {
    $this->trait->dataTypes = [1, 2];

    $query = $this->trait->buildQuerySupportsDataTypes([]);

    expect($query)->toHaveKey('dataTypeIds')
        ->and($query['dataTypeIds'])->toBe([1, 2]);
});

it('builds query without data types when empty', function () {
    $this->trait->dataTypes = [];

    $query = $this->trait->buildQuerySupportsDataTypes([]);

    expect($query)->not->toHaveKey('dataTypeIds');
});
