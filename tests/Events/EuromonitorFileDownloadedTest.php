<?php

use Bernskiold\LaravelEuromonitor\Enum\Dataset;
use Bernskiold\LaravelEuromonitor\Events\EuromonitorFileDownloaded;

it('creates an event with the correct properties', function () {
    $event = new EuromonitorFileDownloaded(
        dataset: Dataset::BrandShare,
        jobId: 'test-job-123',
        filePath: 'euromonitor/path/to/file.json',
        disk: 'local',
    );

    expect($event)
        ->dataset->toBe(Dataset::BrandShare)
        ->jobId->toBe('test-job-123')
        ->filePath->toBe('euromonitor/path/to/file.json')
        ->disk->toBe('local');
});

it('carries the disk the file was written to', function () {
    $event = new EuromonitorFileDownloaded(
        dataset: Dataset::CompanyShare,
        jobId: 'test-job-123',
        filePath: 'euromonitor/path/to/file.json',
        disk: 's3',
    );

    expect($event)
        ->disk->toBe('s3')
        ->dataset->toBe(Dataset::CompanyShare);
});
