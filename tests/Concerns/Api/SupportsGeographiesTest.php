<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsGeographies;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class() {
        use SupportsGeographies;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets geographies from array', function () {
    $this->trait->geographies([1, 2]);
    expect($this->trait->geographies)->toBe([1, 2]);
});

it('sets geographies from single value', function () {
    $this->trait->geographies(1);
    expect($this->trait->geographies)->toBe([1]);
});

it('sets geographies from model', function () {
    $model = new class extends \Illuminate\Database\Eloquent\Model {
        protected $attributes = ['id' => 1];
    };

    $this->trait->geographies($model);
    expect($this->trait->geographies)->toBe([1]);
});

it('sets geographies from collection', function () {
    $collection = collect([1, 2]);

    $this->trait->geographies($collection);
    expect($this->trait->geographies)->toBe([1, 2]);
});

it('sets geographies to empty array when null', function () {
    $this->trait->geographies(null);
    expect($this->trait->geographies)->toBe([]);
});

it('builds query with geographyIds', function () {
    $this->trait->geographies = [1, 2];

    $query = $this->trait->buildQuerySupportsGeographies([]);

    expect($query)->toHaveKey('geographyIds')
        ->and($query['geographyIds'])->toBe([1, 2]);
});

it('builds query without geographyIds when empty', function () {
    $this->trait->geographies = [];

    $query = $this->trait->buildQuerySupportsGeographies([]);

    expect($query)->not->toHaveKey('geographyIds');
});
