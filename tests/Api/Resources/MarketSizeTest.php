<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\Resources\MarketSize;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\MarketSize as MarketSizeResource;
use Bernskiold\LaravelEuromonitor\Data\JobResponse;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Bernskiold\LaravelEuromonitor\Jobs\DownloadEuromonitorFile;
use Illuminate\Support\Facades\Queue;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(\Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient::class);
    $this->resource = new MarketSize($this->client);
});

it('makes correct API call and returns list response', function () {
    $this->resource->categories([1, 2]);

    $apiResponse = (object) [
        'marketSizes' => [
            (object) [
                'researchYear' => 2023,
                'geographyId' => 1,
                'geographyName' => 'Test Geography',
                'categoryId' => 1,
                'categoryName' => 'Test Category',
                'industry' => 'Test Industry',
                'dataTypeId' => 3,
                'dataType' => 'Test Data Type',
                'unitName' => 'USD',
                'inflationType' => 'Current',
                'exchangeRateName' => 'Test Exchange Rate',
                'perCapitaName' => 'Test Per Capita',
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
        ->with('/statistics/marketsizes', Mockery::type('array'))
        ->andReturn($apiResponse);

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(1)
        ->and($response->currentOffset)->toBe(0)
        ->and($response->get()->first())
        ->toBeInstanceOf(MarketSizeResource::class)
        ->and($response->get()->first()->industry)->toBe('Test Industry');
});

it('creates job and dispatches download when requested', function () {
    Queue::fake();

    $this->resource->categories([1, 2]);
    $this->resource->downloadFileAutomatically();

    $apiResponse = (object) [
        'jobId' => 'test-job-123',
        'processingStatus' => 'Queued',
        'message' => 'Job created successfully',
    ];

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/createmarketsizejob', Mockery::type('array'))
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

    $this->resource->categories([1, 2]);
    $this->resource->downloadFileManually();

    $apiResponse = (object) [
        'jobId' => 'test-job-123',
        'processingStatus' => 'Queued',
        'message' => 'Job created successfully',
    ];

    $this->client->shouldReceive('post')
        ->once()
        ->with('/statistics/createmarketsizejob', Mockery::type('array'))
        ->andReturn($apiResponse);

    $response = $this->resource->dispatch();

    expect($response)
        ->toBeInstanceOf(JobResponse::class)
        ->and($response->jobId)->toBe('test-job-123');

    Queue::assertNotPushed(DownloadEuromonitorFile::class);
});

it('validates either categoryIds or industryCodes is required', function () {
    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);

    $this->resource->categories([1, 2]);
    expect(fn () => $this->resource->all())
        ->not->toThrow(InvalidEuromonitorQueryException::class);

    $this->resource->categories = [];
    $this->resource->industryCodes(['TEST123']);
    expect(fn () => $this->resource->all())
        ->not->toThrow(InvalidEuromonitorQueryException::class);
});
