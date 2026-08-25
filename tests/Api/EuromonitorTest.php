<?php

namespace Tests\Api;

use Bernskiold\LaravelEuromonitor\Api\Euromonitor;
use Bernskiold\LaravelEuromonitor\Api\Resources\BrandShare;
use Bernskiold\LaravelEuromonitor\Api\Resources\Brands;
use Bernskiold\LaravelEuromonitor\Api\Resources\Categories;
use Bernskiold\LaravelEuromonitor\Api\Resources\Companies;
use Bernskiold\LaravelEuromonitor\Api\Resources\CompanyShare;
use Bernskiold\LaravelEuromonitor\Api\Resources\DataTypes;
use Bernskiold\LaravelEuromonitor\Api\Resources\Geographies;
use Bernskiold\LaravelEuromonitor\Api\Resources\Jobs;
use Bernskiold\LaravelEuromonitor\Api\Resources\MarketSize;
use Mockery;

beforeEach(function () {
    $this->apiClient = Mockery::mock(\Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient::class);
    $this->euromonitor = new Euromonitor($this->apiClient);
});

afterEach(function () {
    Mockery::close();
});

it('returns BrandShare instance', function () {
    $resource = $this->euromonitor->brandShares();

    expect($resource)->toBeInstanceOf(BrandShare::class);
});

it('returns Categories instance', function () {
    $resource = $this->euromonitor->categories();

    expect($resource)->toBeInstanceOf(Categories::class);
});

it('returns CompanyShare instance', function () {
    $resource = $this->euromonitor->companyShares();

    expect($resource)->toBeInstanceOf(CompanyShare::class);
});

it('returns DataTypes instance', function () {
    $resource = $this->euromonitor->dataTypes();

    expect($resource)->toBeInstanceOf(DataTypes::class);
});

it('returns Geographies instance', function () {
    $resource = $this->euromonitor->geographies();

    expect($resource)->toBeInstanceOf(Geographies::class);
});

it('returns Jobs instance', function () {
    $resource = $this->euromonitor->jobs();

    expect($resource)->toBeInstanceOf(Jobs::class);
});

it('returns MarketSize instance', function () {
    $resource = $this->euromonitor->marketSize();

    expect($resource)->toBeInstanceOf(MarketSize::class);
});

it('returns Brands instance', function () {
    $resource = $this->euromonitor->brands();

    expect($resource)->toBeInstanceOf(Brands::class);
});

it('returns Companies instance', function () {
    $resource = $this->euromonitor->companies();

    expect($resource)->toBeInstanceOf(Companies::class);
});

it('returns ShareType instance', function () {
    $resource = $this->euromonitor->shareTypes();

    expect($resource)->toBeInstanceOf(\Bernskiold\LaravelEuromonitor\Api\Resources\ShareType::class);
});

it('returns a fresh resource on every call', function () {
    expect($this->euromonitor->brands())->not->toBe($this->euromonitor->brands())
        ->and($this->euromonitor->companies())->not->toBe($this->euromonitor->companies());
});
