<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Api\Resources\Companies;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\Company;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(EuromonitorApiClient::class);
    $this->resource = new Companies($this->client);
});

it('calls the catalog company endpoint and returns a list response', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/company', Mockery::type('array'))
        ->andReturn(sample('catalog/company'));

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(3)
        ->and($response->get()->first())->toBeInstanceOf(Company::class);
});

it('maps the company record', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->andReturn(sample('catalog/company'));

    $company = $this->resource->all()->get()->first();

    expect($company)
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->geographyId->toBe(21)
        ->geographyName->toBe('Belgium')
        ->categoryId->toBe(11069)
        ->categoryName->toBe('Toilet Paper')
        ->industryCode->toBe('CT')
        ->industryName->toBe('Consumer Tissue');
});

it('returns the same company id across geographies', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->andReturn(sample('catalog/company'));

    $essity = $this->resource->all()->get()->where('companyName', 'Essity AB');

    expect($essity)->toHaveCount(2)
        ->and($essity->pluck('companyId')->unique()->all())->toBe([674613]);
});

it('sends pascal case pagination and filter keys', function () {
    $this->resource
        ->page(2)
        ->perPage(1_000)
        ->geographies([21])
        ->categories([11069]);

    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/company', Mockery::on(
            fn (array $query) => $query['PageNumber'] === 2
                && $query['NoOfRecords'] === 1_000
                && $query['GeographyIds'] === [21]
                && $query['CategoryIds'] === [11069]
        ))
        ->andReturn(sample('catalog/company'));

    $this->resource->all();
});

it('rejects a page size above the maximum', function () {
    $this->resource->perPage(10_001);

    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);
});

it('handles an empty catalog response', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->andReturn([]);

    expect($this->resource->all()->get())->toHaveCount(0);
});
