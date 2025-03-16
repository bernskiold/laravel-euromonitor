<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\Resources\Jobs;
use Bernskiold\LaravelEuromonitor\Data\JobDownloadResponse;
use Bernskiold\LaravelEuromonitor\Data\JobResponse;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(\Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient::class);
    $this->resource = new Jobs($this->client);
});

it('gets download url for job', function () {
    $jobId = 'test-job-123';
    $apiResponse = (object) [
        'jobDownloadUri' => 'https://example.com/download.csv',
        'message' => 'Download URL retrieved successfully',
        'status' => 'Processed',
    ];

    $this->client->shouldReceive('get')
        ->once()
        ->with("/statistics/getjobdownloadurl/{$jobId}")
        ->andReturn($apiResponse);

    $response = $this->resource->getDownloadUrl($jobId);

    expect($response)
        ->toBeInstanceOf(JobDownloadResponse::class)
        ->and($response->url)->toBe('https://example.com/download.csv')
        ->and($response->message)->toBe('Download URL retrieved successfully')
        ->and($response->status)->toBe('Processed');
});

it('gets status for job', function () {
    $jobId = 'test-job-123';
    $apiResponse = (object) [
        'jobId' => $jobId,
        'processingStatus' => 'Processed',
        'message' => 'Job completed successfully',
    ];

    $this->client->shouldReceive('get')
        ->once()
        ->with("/statistics/getjobstatus/{$jobId}")
        ->andReturn($apiResponse);

    $response = $this->resource->getStatus($jobId);

    expect($response)
        ->toBeInstanceOf(JobResponse::class)
        ->and($response->jobId)->toBe($jobId)
        ->and($response->status)->toBe('Processed')
        ->and($response->message)->toBe('Job completed successfully');
});

it('cancels job', function () {
    $jobId = 'test-job-123';
    $apiResponse = (object) [
        'status' => 'Canceled',
        'message' => 'Job cancelled successfully',
    ];

    $this->client->shouldReceive('get')
        ->once()
        ->with("/statistics/cancelJob/{$jobId}")
        ->andReturn($apiResponse);

    $response = $this->resource->cancel($jobId);

    expect($response)
        ->toBeInstanceOf(JobResponse::class)
        ->and($response->jobId)->toBe($jobId)
        ->and($response->status)->toBe('Canceled')
        ->and($response->message)->toBe('Job cancelled successfully');
});
