<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Api\Resources\ShareType;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\ShareType as ShareTypeResource;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Enum\MeasureType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(EuromonitorApiClient::class);
    $this->resource = new ShareType($this->client);
});

it('throws when no measure type is set', function () {
    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);
});

it('posts to the brand share sharetype endpoint', function () {
    $this->resource->measureType(MeasureType::BrandShare);
    $this->resource->industryCodes(['CT']);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/catalog/BrandShare/sharetype', Mockery::type('array'))
        ->andReturn(sample('catalog/sharetype'));

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(2)
        ->and($response->get()->first())->toBeInstanceOf(ShareTypeResource::class);
});

it('posts to the company share sharetype endpoint', function () {
    $this->resource->measureType(MeasureType::CompanyShare);
    $this->resource->industryCodes(['CT']);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/catalog/CompanyShare/sharetype', Mockery::type('array'))
        ->andReturn(sample('catalog/sharetype'));

    $this->resource->all();
});

it('maps the share type definition', function () {
    $this->resource->measureType(MeasureType::BrandShare);
    $this->resource->industryCodes(['CT']);

    $this->client->shouldReceive('post')->once()->andReturn(sample('catalog/sharetype'));

    $shareType = $this->resource->all()->get()->first();

    expect($shareType)
        ->id->toBe(0)
        ->name->toBe('Global Brand Owner')
        ->and($shareType->definition)->toContain('global level');
});

it('sends the industry codes and categories it was given', function () {
    $this->resource->measureType(MeasureType::BrandShare);
    $this->resource->industryCodes(['CT']);
    $this->resource->categories([11069]);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/catalog/BrandShare/sharetype', Mockery::on(
            fn (array $query) => $query['industryCodes'] === ['CT']
                && $query['categoryIds'] === [11069]
        ))
        ->andReturn(sample('catalog/sharetype'));

    $this->resource->all();
});
