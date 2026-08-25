<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Api\Resources\CompanyShare;
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
    $this->resource = new CompanyShare($this->client);
});

function validCompanyShareQuery(CompanyShare $resource): CompanyShare
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
    validCompanyShareQuery($this->resource);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/companyshare/Percentage', Mockery::type('array'))
        ->andReturn(sample('statistics/companyshare'));

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(2)
        ->and($response->currentOffset)->toBe(0)
        ->and($response->get())->toHaveCount(2);
});

it('maps the euromonitor company identifier', function () {
    validCompanyShareQuery($this->resource);

    $this->client->shouldReceive('post')
        ->once()
        ->andReturn(sample('statistics/companyshare'));

    $share = $this->resource->all()->get()->first();

    expect($share)
        ->toBeInstanceOf(EntityShare::class)
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->brandId->toBeNull()
        ->brandName->toBeNull()
        ->geographyId->toBe(21)
        ->researchYear->toBe(2024);

    expect($share->entityId())->toBe(674613)
        ->and($share->entityName())->toBe('Essity AB');
});

it('filters a live query by company ids', function () {
    validCompanyShareQuery($this->resource)->companies([674613, 674614]);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/companyshare/Percentage', Mockery::on(
            fn (array $query) => ($query['companyIds'] ?? null) === [674613, 674614]
        ))
        ->andReturn(sample('statistics/companyshare'));

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
        ->with('/statistics/createcompanysharejob', Mockery::type('array'))
        ->andReturn(sample('statistics/job'));

    $response = $this->resource->dispatch();

    expect($response)
        ->toBeInstanceOf(JobResponse::class)
        ->jobId->toBe('0f3b6a02-9a1e-4b0e-9f6c-2f7f2b0c9a11')
        ->status->toBe('Queued');

    Queue::assertPushed(DownloadEuromonitorFile::class);
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
        ->with('/statistics/createcompanysharejob', Mockery::type('array'))
        ->andReturn(sample('statistics/job'));

    $this->resource->dispatch();

    Queue::assertNotPushed(DownloadEuromonitorFile::class);
});

it('sends pascal case company ids when dispatching a mass extraction', function () {
    Queue::fake();

    $this->resource
        ->unit(UnitType::Percentage)
        ->shareType(0)
        ->geographies([21])
        ->industryCodes(['CT'])
        ->companies([674613]);

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/createcompanysharejob', Mockery::on(
            fn (array $query) => ($query['CompanyIds'] ?? null) === [674613]
                && ($query['IndustryCode'] ?? null) === 'CT'
        ))
        ->andReturn(sample('statistics/job'));

    $this->resource->dispatch();
});

it('validates either categoryIds or industryCodes is required', function () {
    $this->resource
        ->unit(UnitType::Percentage)
        ->shareType(0)
        ->geographies([21]);

    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);
});
