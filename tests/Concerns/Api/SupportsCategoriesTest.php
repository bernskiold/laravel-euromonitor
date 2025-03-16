<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsCategories;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class() {
        use SupportsCategories;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets categories from array', function () {
    $this->trait->categories([1, 2]);
    expect($this->trait->categories)->toBe([1, 2]);
});

it('sets categories from single value', function () {
    $this->trait->categories(1);
    expect($this->trait->categories)->toBe([1]);
});

it('sets categories from model', function () {
    $model = new class extends \Illuminate\Database\Eloquent\Model {
        protected $attributes = ['id' => 1];
    };

    $this->trait->categories($model);
    expect($this->trait->categories)->toBe([1]);
});

it('sets categories from collection', function () {
    $collection = collect([1, 2]);

    $this->trait->categories($collection);
    expect($this->trait->categories)->toBe([1, 2]);
});

it('sets categories to empty array when null', function () {
    $this->trait->categories(null);
    expect($this->trait->categories)->toBe([]);
});


it('builds query with categoryIds', function () {
    $this->trait->categories = [1, 2];

    $query = $this->trait->buildQuerySupportsCategories([]);

    expect($query)->toHaveKey('categoryIds')
        ->and($query['categoryIds'])->toBe([1, 2]);
});
