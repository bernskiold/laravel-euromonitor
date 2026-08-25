<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Api\Resources\Brands;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\Brand;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(EuromonitorApiClient::class);
    $this->resource = new Brands($this->client);
});

it('calls the catalog brand endpoint and returns a list response', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/brand', Mockery::type('array'))
        ->andReturn(sample('catalog/brand'));

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(3)
        ->and($response->get())->toHaveCount(3)
        ->and($response->get()->first())->toBeInstanceOf(Brand::class);
});

it('maps the brand and its owning company', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->andReturn(sample('catalog/brand'));

    $brand = $this->resource->all()->get()->first();

    expect($brand)
        ->brandId->toBe(903112)
        ->brandName->toBe('Lotus')
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->geographyId->toBe(21)
        ->geographyName->toBe('Belgium')
        ->categoryId->toBe(11069)
        ->categoryName->toBe('Toilet Paper')
        ->industryCode->toBe('CT')
        ->industryName->toBe('Consumer Tissue');
});

it('returns the same brand id across geographies', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->andReturn(sample('catalog/brand'));

    $brands = $this->resource->all()->get();

    $lotus = $brands->where('brandName', 'Lotus');

    expect($lotus)->toHaveCount(2)
        ->and($lotus->pluck('brandId')->unique()->all())->toBe([903112])
        ->and($lotus->pluck('geographyId')->all())->toBe([21, 11]);
});

it('defaults to the first page and the maximum page size', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/brand', Mockery::on(
            fn (array $query) => $query['PageNumber'] === 1 && $query['NoOfRecords'] === 10_000
        ))
        ->andReturn(sample('catalog/brand'));

    $this->resource->all();
});

it('sends pascal case pagination and filter keys', function () {
    $this->resource
        ->page(3)
        ->perPage(500)
        ->geographies([21, 11])
        ->categories([11069]);

    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/brand', Mockery::on(
            fn (array $query) => $query['PageNumber'] === 3
                && $query['NoOfRecords'] === 500
                && $query['GeographyIds'] === [21, 11]
                && $query['CategoryIds'] === [11069]
                && ! array_key_exists('geographyIds', $query)
                && ! array_key_exists('categoryIds', $query)
        ))
        ->andReturn(sample('catalog/brand'));

    $this->resource->all();
});

it('reports the current offset for the requested page', function () {
    $this->resource->page(3)->perPage(500);

    $this->client->shouldReceive('get')
        ->once()
        ->andReturn(sample('catalog/brand'));

    expect($this->resource->all()->currentOffset)->toBe(1_000);
});

it('rejects a page size above the maximum', function () {
    $this->resource->perPage(10_001);

    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);
});

it('rejects a page number below one', function () {
    $this->resource->page(0);

    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);
});

it('handles an empty catalog response', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->andReturn([]);

    $response = $this->resource->all();

    expect($response->totalRecords)->toBe(0)
        ->and($response->get())->toHaveCount(0);
});
