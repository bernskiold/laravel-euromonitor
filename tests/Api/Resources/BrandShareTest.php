<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Api\Resources\BrandShare;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\EntityShare;
use Bernskiold\LaravelEuromonitor\Data\JobResponse;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Enum\UnitType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Bernskiold\LaravelEuromonitor\Jobs\DownloadEuromonitorFile;
use Illuminate\Support\Facades\Queue;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(EuromonitorApiClient::class);
    $this->resource = new BrandShare($this->client);
});

/**
 * A minimally valid live query, so each test only has to add the
 * parameter it is actually asserting on.
 */
function validBrandShareQuery(BrandShare $resource): BrandShare
{
    return $resource
        ->unit(UnitType::Percentage)
        ->shareType(0)
        ->geographies([21])
        ->categories([11069]);
}

it('throws exception when unit is not set', function () {
    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);
});

it('makes correct API call and returns list response', function () {
    validBrandShareQuery($this->resource);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/brandshare/Percentage', Mockery::type('array'))
        ->andReturn(sample('statistics/brandshare'));

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(2)
        ->and($response->currentOffset)->toBe(0)
        ->and($response->get())->toHaveCount(2);
});

it('maps the euromonitor brand and company identifiers', function () {
    validBrandShareQuery($this->resource);

    $this->client->shouldReceive('post')
        ->once()
        ->andReturn(sample('statistics/brandshare'));

    $share = $this->resource->all()->get()->first();

    expect($share)
        ->toBeInstanceOf(EntityShare::class)
        ->brandId->toBe(903112)
        ->brandName->toBe('Lotus')
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->geographyId->toBe(21)
        ->categoryId->toBe(11069)
        ->researchYear->toBe(2024);

    expect($share->entityId())->toBe(903112)
        ->and($share->entityName())->toBe('Lotus')
        ->and($share->hasEntityId())->toBeTrue();
});

it('maps the yearly data points', function () {
    validBrandShareQuery($this->resource);

    $this->client->shouldReceive('post')
        ->once()
        ->andReturn(sample('statistics/brandshare'));

    $share = $this->resource->all()->get()->first();

    expect($share->data)->toHaveCount(3)
        ->and($share->data->first()->year)->toBe(2022)
        ->and($share->data->first()->value)->toBe(21.4)
        ->and($share->data->last()->value)->toBe(22.8);
});

it('filters a live query by brand ids', function () {
    validBrandShareQuery($this->resource)->brands([903112, 903118]);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/brandshare/Percentage', Mockery::on(
            fn (array $query) => ($query['brandIds'] ?? null) === [903112, 903118]
        ))
        ->andReturn(sample('statistics/brandshare'));

    $this->resource->all();
});

it('filters a live query by company ids', function () {
    validBrandShareQuery($this->resource)->companies([674613]);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/brandshare/Percentage', Mockery::on(
            fn (array $query) => ($query['companyIds'] ?? null) === [674613]
        ))
        ->andReturn(sample('statistics/brandshare'));

    $this->resource->all();
});

it('omits the id filters when none are set', function () {
    validBrandShareQuery($this->resource);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/brandshare/Percentage', Mockery::on(
            fn (array $query) => ! array_key_exists('brandIds', $query)
                && ! array_key_exists('companyIds', $query)
        ))
        ->andReturn(sample('statistics/brandshare'));

    $this->resource->all();
});

it('creates job and dispatches download when requested', function () {
    Queue::fake();

    $this->resource
        ->unit(UnitType::Percentage)
        ->shareType(0)
        ->geographies([21])
        ->industryCodes(['CT']);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/createbrandsharejob', Mockery::type('array'))
        ->andReturn(sample('statistics/job'));

    $response = $this->resource->dispatch();

    expect($response)
        ->toBeInstanceOf(JobResponse::class)
        ->jobId->toBe('0f3b6a02-9a1e-4b0e-9f6c-2f7f2b0c9a11')
        ->status->toBe('Queued');

    Queue::assertPushed(
        DownloadEuromonitorFile::class,
        fn ($job) => $job->jobId === '0f3b6a02-9a1e-4b0e-9f6c-2f7f2b0c9a11'
    );
});

it('does not dispatch download when automaticallyDownloadFile is false', function () {
    Queue::fake();

    $this->resource
        ->unit(UnitType::Percentage)
        ->shareType(0)
        ->geographies([21])
        ->industryCodes(['CT'])
        ->downloadFileManually();

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/createbrandsharejob', Mockery::type('array'))
        ->andReturn(sample('statistics/job'));

    $this->resource->dispatch();

    Queue::assertNotPushed(DownloadEuromonitorFile::class);
});

it('sends pascal case id filters when dispatching a mass extraction', function () {
    Queue::fake();

    $this->resource
        ->unit(UnitType::Percentage)
        ->shareType(0)
        ->geographies([21])
        ->industryCodes(['CT'])
        ->brands([903112])
        ->companies([674613]);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/createbrandsharejob', Mockery::on(
            fn (array $query) => ($query['BrandIds'] ?? null) === [903112]
                && ($query['CompanyIds'] ?? null) === [674613]
                && ($query['GeographyIds'] ?? null) === [21]
                && ($query['IndustryCode'] ?? null) === 'CT'
        ))
        ->andReturn(sample('statistics/job'));

    $this->resource->dispatch();
});

it('validates required fields', function () {
    $this->resource->unit(UnitType::Percentage);

    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);

    $this->resource->geographies([21]);
    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);
});

it('validates either categoryIds or industryCodes is required', function () {
    $this->resource
        ->unit(UnitType::Percentage)
        ->shareType(0)
        ->geographies([21]);

    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);

    $this->resource->industryCodes(['CT']);

    $this->client->shouldReceive('post')
        ->once()
        ->andReturn(sample('statistics/brandshare'));

    expect(fn () => $this->resource->all())
        ->not->toThrow(InvalidEuromonitorQueryException::class);
});

it('rejects more than one industry code for mass extraction', function () {
    $this->resource
        ->unit(UnitType::Percentage)
        ->shareType(0)
        ->geographies([21])
        ->industryCodes(['CT', 'DPP']);

    expect(fn () => $this->resource->dispatch())
        ->toThrow(InvalidEuromonitorQueryException::class);
});
