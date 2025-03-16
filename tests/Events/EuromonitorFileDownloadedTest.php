<?php

use Bernskiold\LaravelEuromonitor\Events\EuromonitorFileDownloaded;

beforeEach(function () {
    config(['euromonitor.mass_extraction.disk' => 'local']);
});

it('creates an event with the correct properties', function () {
    $event = new EuromonitorFileDownloaded(
        jobId: 'test-job-123',
        filePath: 'path/to/file.csv'
    );

    expect($event->jobId)->toBe('test-job-123')
        ->and($event->filePath)->toBe('path/to/file.csv')
        ->and($event->disk)->toBe('local');
});

it('uses custom disk from config when specified', function () {
    config(['euromonitor.mass_extraction.disk' => 's3']);

    $event = new EuromonitorFileDownloaded(
        jobId: 'test-job-123',
        filePath: 'path/to/file.csv'
    );

    expect($event->disk)->toBe('s3');
});
