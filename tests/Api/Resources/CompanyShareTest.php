<?php

namespace Tests\Api\Resources;

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
    $this->client = Mockery::mock(\Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient::class);
    $this->resource = new CompanyShare($this->client);
});

it('makes correct API call and returns list response', function () {
    $this->resource->unit(UnitType::Percentage);
    $this->resource->shareType(1);
    $this->resource->geographies([1, 2]);
    $this->resource->categories([3, 4]);

    $apiResponse = (object) [
        'companyShares' => [
            (object) [
                'researchYear' => 2023,
                'geographyId' => 1,
                'geographyName' => 'Test Geography',
                'categoryId' => 3,
                'categoryName' => 'Test Category',
                'industryCode' => 'TEST123',
                'industryName' => 'Test Industry',
                'entityName' => 'Test Entity',
                'dataTypeId' => 5,
                'dataTypeName' => 'Test Data Type',
                'shareTypeId' => 1,
                'shareTypeName' => 'Test Share Type',
                'exchangeRateName' => 'Test Exchange Rate',
                'unit' => 'USD',
                'unitMultiplier' => 1,
                'data' => [
                    (object) ['year' => 2023, 'value' => 123.45],
                ],
            ],
        ],
        'total' => 1,
        'offset' => 0,
    ];

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/companyshare/Percentage', Mockery::type('array'))
        ->andReturn($apiResponse);

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(1)
        ->and($response->currentOffset)->toBe(0)
        ->and($response->get()->first())
        ->toBeInstanceOf(EntityShare::class)
        ->and($response->get()->first()->entityName)->toBe('Test Entity');
});

it('creates job and dispatches download when requested', function () {
    Queue::fake();

    $this->resource->unit(UnitType::Percentage);
    $this->resource->shareType(1);
    $this->resource->geographies([1, 2]);
    $this->resource->categories([3, 4]);
    $this->resource->downloadFileAutomatically();

    $apiResponse = (object) [
        'jobId' => 'test-job-123',
        'processingStatus' => 'Queued',
        'message' => 'Job created successfully',
    ];

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/createcompanysharejob', Mockery::type('array'))
        ->andReturn($apiResponse);

    $response = $this->resource->dispatch();

    expect($response)
        ->toBeInstanceOf(JobResponse::class)
        ->and($response->jobId)->toBe('test-job-123')
        ->and($response->status)->toBe('Queued')
        ->and($response->message)->toBe('Job created successfully');

    Queue::assertPushed(DownloadEuromonitorFile::class, function ($job) {
        return $job->jobId === 'test-job-123';
    });
});

it('does not dispatch download when automaticallyDownloadFile is false', function () {
    Queue::fake();

    $this->resource->unit(UnitType::Percentage);
    $this->resource->shareType(1);
    $this->resource->geographies([1, 2]);
    $this->resource->categories([3, 4]);
    $this->resource->downloadFileManually();

    $apiResponse = (object) [
        'jobId' => 'test-job-123',
        'processingStatus' => 'Queued',
        'message' => 'Job created successfully',
    ];

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/createcompanysharejob', Mockery::type('array'))
        ->andReturn($apiResponse);

    $response = $this->resource->dispatch();

    expect($response)
        ->toBeInstanceOf(JobResponse::class)
        ->and($response->jobId)->toBe('test-job-123');

    Queue::assertNotPushed(DownloadEuromonitorFile::class);
});

it('validates required fields', function () {
    $this->resource->unit(UnitType::Percentage);

    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);

    $this->resource->shareType(1);
    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);

    $this->resource->geographies([1, 2]);
    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);

    $this->resource->categories([3, 4]);
    expect(fn () => $this->resource->all())
        ->not->toThrow(InvalidEuromonitorQueryException::class);
});

it('validates either categoryIds or industryCodes is required', function () {
    $this->resource->unit(UnitType::Percentage);
    $this->resource->shareType(1);
    $this->resource->geographies([1, 2]);

    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);

    $this->resource->industryCodes(['TEST123']);
    expect(fn () => $this->resource->all())
        ->not->toThrow(InvalidEuromonitorQueryException::class);
});
