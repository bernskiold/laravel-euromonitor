<?php

use Bernskiold\LaravelEuromonitor\Data\JobResponse;
use Bernskiold\LaravelEuromonitor\Exceptions\EuromonitorDownloadException;

it('throws an exception with the correct message when job status is not supported', function () {
    $response = new JobResponse(
        jobId: 'test-job-id',
        status: 'invalid_status',
        message: 'Test message'
    );
    $exception = EuromonitorDownloadException::invalidStatus($response);

    expect($exception)
        ->toBeInstanceOf(EuromonitorDownloadException::class)
        ->getMessage()->toBe('Could not download Euromonitor file - status not supported: invalid_status');
});
