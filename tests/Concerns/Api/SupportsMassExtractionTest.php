<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsMassExtraction;
use Bernskiold\LaravelEuromonitor\Enum\FileType;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource {
        use SupportsMassExtraction;
    };
});

afterEach(function () {
    Mockery::close();
});

it('builds query with FileType when mass extraction is enabled', function () {
    $this->trait->usesMassExtraction = true;
    $this->trait->fileType = FileType::Json;

    $query = $this->trait->buildQuerySupportsMassExtraction([]);

    expect($query)->toHaveKey('FileType')
        ->and($query['FileType'])->toBe(FileType::Json->code());
});

it('does not add FileType to query when mass extraction is disabled', function () {
    $this->trait->usesMassExtraction = false;

    $query = $this->trait->buildQuerySupportsMassExtraction([]);

    expect($query)->not->toHaveKey('FileType');
});

it('enables mass extraction with queueable method', function () {
    $this->trait->queueable();

    expect($this->trait->usesMassExtraction)->toBe(true);
});

it('sets automatic file download', function () {
    $this->trait->downloadFileAutomatically();

    expect($this->trait->automaticallyDownloadFile)->toBe(true);
});

it('sets manual file download', function () {
    $this->trait->downloadFileManually();

    expect($this->trait->automaticallyDownloadFile)->toBe(false);
});

it('sets file type to JSON', function () {
    $this->trait->asJson();

    expect($this->trait->fileType)->toBe(FileType::Json);
});

it('sets file type to CSV', function () {
    $this->trait->asCsv();

    expect($this->trait->fileType)->toBe(FileType::Csv);
});

it('sets file type to Parquet', function () {
    $this->trait->asParquet();

    expect($this->trait->fileType)->toBe(FileType::Parquet);
});
