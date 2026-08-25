<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsCompanies;
use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Illuminate\Database\Eloquent\Model;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource
    {
        use SupportsCompanies;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets companies from array', function () {
    $this->trait->companies([674613, 674614]);
    expect($this->trait->companies)->toBe([674613, 674614]);
});

it('sets companies from single value', function () {
    $this->trait->companies(674613);
    expect($this->trait->companies)->toBe([674613]);
});

it('sets companies from a comma separated string', function () {
    $this->trait->companies('674613,674614');
    expect($this->trait->companies)->toBe(['674613', '674614']);
});

it('sets companies from model', function () {
    $model = new class extends Model
    {
        protected $attributes = ['id' => 674613];
    };

    $this->trait->companies($model);
    expect($this->trait->companies)->toBe([674613]);
});

it('sets companies from collection', function () {
    $this->trait->companies(collect([674613, 674614]));
    expect($this->trait->companies)->toBe([674613, 674614]);
});

it('sets companies to empty array when null', function () {
    $this->trait->companies(null);
    expect($this->trait->companies)->toBe([]);
});

it('builds query with camel case companyIds for the statistics services', function () {
    $this->trait->companies = [674613];

    $query = $this->trait->buildQuerySupportsCompanies([]);

    expect($query)->toHaveKey('companyIds')
        ->and($query['companyIds'])->toBe([674613]);
});

it('builds query with pascal case CompanyIds for mass extraction', function () {
    $this->trait->usesMassExtraction = true;
    $this->trait->companies = [674613];

    $query = $this->trait->buildQuerySupportsCompanies([]);

    expect($query)->toHaveKey('CompanyIds')
        ->and($query['CompanyIds'])->toBe([674613]);
});

it('omits the key entirely when no companies are set', function () {
    $query = $this->trait->buildQuerySupportsCompanies([]);

    expect($query)->toBe([]);
});
