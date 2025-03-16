<?php

namespace Tests\Facades;

use Bernskiold\LaravelEuromonitor\Api\Euromonitor;
use Bernskiold\LaravelEuromonitor\Api\Resources\BrandShare;
use Bernskiold\LaravelEuromonitor\Api\Resources\Categories;
use Bernskiold\LaravelEuromonitor\Api\Resources\CompanyShare;
use Bernskiold\LaravelEuromonitor\Api\Resources\DataTypes;
use Bernskiold\LaravelEuromonitor\Api\Resources\Geographies;
use Bernskiold\LaravelEuromonitor\Api\Resources\Jobs;
use Bernskiold\LaravelEuromonitor\Api\Resources\MarketSize;
use Bernskiold\LaravelEuromonitor\Facades\Euromonitor as EuromonitorFacade;
use Illuminate\Support\Facades\App;
use Mockery;

beforeEach(function () {
    $this->euromonitor = Mockery::mock(Euromonitor::class);
    App::instance('laravel-euromonitor', $this->euromonitor);
});

afterEach(function () {
    Mockery::close();
});

it('resolves the Euromonitor instance from the container', function () {
    expect(App::make('laravel-euromonitor'))->toBe($this->euromonitor);
});

it('forwards brandShares call to the underlying instance', function () {
    $expectedResource = Mockery::mock(BrandShare::class);
    $this->euromonitor->shouldReceive('brandShares')->once()->andReturn($expectedResource);

    $resource = EuromonitorFacade::brandShares();

    expect($resource)->toBe($expectedResource);
});

it('forwards categories call to the underlying instance', function () {
    $expectedResource = Mockery::mock(Categories::class);
    $this->euromonitor->shouldReceive('categories')->once()->andReturn($expectedResource);

    $resource = EuromonitorFacade::categories();

    expect($resource)->toBe($expectedResource);
});

it('forwards companyShares call to the underlying instance', function () {
    $expectedResource = Mockery::mock(CompanyShare::class);
    $this->euromonitor->shouldReceive('companyShares')->once()->andReturn($expectedResource);

    $resource = EuromonitorFacade::companyShares();

    expect($resource)->toBe($expectedResource);
});

it('forwards dataTypes call to the underlying instance', function () {
    $expectedResource = Mockery::mock(DataTypes::class);
    $this->euromonitor->shouldReceive('dataTypes')->once()->andReturn($expectedResource);

    $resource = EuromonitorFacade::dataTypes();

    expect($resource)->toBe($expectedResource);
});

it('forwards geographies call to the underlying instance', function () {
    $expectedResource = Mockery::mock(Geographies::class);
    $this->euromonitor->shouldReceive('geographies')->once()->andReturn($expectedResource);

    $resource = EuromonitorFacade::geographies();

    expect($resource)->toBe($expectedResource);
});

it('forwards jobs call to the underlying instance', function () {
    $expectedResource = Mockery::mock(Jobs::class);
    $this->euromonitor->shouldReceive('jobs')->once()->andReturn($expectedResource);

    $resource = EuromonitorFacade::jobs();

    expect($resource)->toBe($expectedResource);
});

it('forwards marketSize call to the underlying instance', function () {
    $expectedResource = Mockery::mock(MarketSize::class);
    $this->euromonitor->shouldReceive('marketSize')->once()->andReturn($expectedResource);

    $resource = EuromonitorFacade::marketSize();

    expect($resource)->toBe($expectedResource);
});
