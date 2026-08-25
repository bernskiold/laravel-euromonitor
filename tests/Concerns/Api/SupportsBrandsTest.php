<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsBrands;
use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Illuminate\Database\Eloquent\Model;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource
    {
        use SupportsBrands;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets brands from array', function () {
    $this->trait->brands([903112, 903118]);
    expect($this->trait->brands)->toBe([903112, 903118]);
});

it('sets brands from single value', function () {
    $this->trait->brands(903112);
    expect($this->trait->brands)->toBe([903112]);
});

it('sets brands from a comma separated string', function () {
    $this->trait->brands('903112,903118');
    expect($this->trait->brands)->toBe(['903112', '903118']);
});

it('sets brands from model', function () {
    $model = new class extends Model
    {
        protected $attributes = ['id' => 903112];
    };

    $this->trait->brands($model);
    expect($this->trait->brands)->toBe([903112]);
});

it('sets brands from collection', function () {
    $this->trait->brands(collect([903112, 903118]));
    expect($this->trait->brands)->toBe([903112, 903118]);
});

it('sets brands to empty array when null', function () {
    $this->trait->brands(null);
    expect($this->trait->brands)->toBe([]);
});

it('builds query with camel case brandIds for the statistics services', function () {
    $this->trait->brands = [903112];

    $query = $this->trait->buildQuerySupportsBrands([]);

    expect($query)->toHaveKey('brandIds')
        ->and($query['brandIds'])->toBe([903112]);
});

it('builds query with pascal case BrandIds for mass extraction', function () {
    $this->trait->usesMassExtraction = true;
    $this->trait->brands = [903112];

    $query = $this->trait->buildQuerySupportsBrands([]);

    expect($query)->toHaveKey('BrandIds')
        ->and($query['BrandIds'])->toBe([903112]);
});

it('omits the key entirely when no brands are set', function () {
    $query = $this->trait->buildQuerySupportsBrands([]);

    expect($query)->toBe([]);
});
