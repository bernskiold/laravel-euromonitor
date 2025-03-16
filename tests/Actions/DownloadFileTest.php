<?php

use Bernskiold\LaravelEuromonitor\Actions\DownloadFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('s3');

    // Mock HTTP responses
    Http::fake([
        'https://example.com/test.csv' => Http::response('test,csv,content', 200),
        'https://invalid-url-that-does-not-exist.com/test.csv' => Http::response('', 404),
        '*' => Http::response('', 404),
    ]);
});

it('downloads a file to the default location', function () {
    // Arrange
    $fileUrl = 'https://example.com/test.csv';
    $action = new DownloadFile();

    // Act
    $path = $action->execute($fileUrl);

    // Assert
    expect($path)->toBe('euromonitor/test.csv');
    expect(Storage::disk('local')->exists('euromonitor/test.csv'))->toBeTrue();
    expect(Storage::disk('local')->get('euromonitor/test.csv'))->toBe('test,csv,content');
});

it('uses custom disk and folder from config', function () {
    // Arrange
    Config::set('euromonitor.mass_extraction.disk', 's3');
    Config::set('euromonitor.mass_extraction.folder', 'custom-folder');
    $fileUrl = 'https://example.com/test.csv';
    $action = new DownloadFile();

    // Act
    $path = $action->execute($fileUrl);

    // Assert
    expect($path)->toBe('custom-folder/test.csv');
    expect(Storage::disk('s3')->exists('custom-folder/test.csv'))->toBeTrue();
    expect(Storage::disk('s3')->get('custom-folder/test.csv'))->toBe('test,csv,content');
});

it('executes callback after successful download', function () {
    // Arrange
    $fileUrl = 'https://example.com/test.csv';
    $action = new DownloadFile();
    $callbackExecuted = false;
    $callbackPath = null;
    $callbackDisk = null;

    $callback = function ($path, $disk) use (&$callbackExecuted, &$callbackPath, &$callbackDisk) {
        $callbackExecuted = true;
        $callbackPath = $path;
        $callbackDisk = $disk;
    };

    // Act
    $path = $action->execute($fileUrl, $callback);

    // Assert
    expect($callbackExecuted)->toBeTrue()
        ->and($callbackPath)->toBe('euromonitor/test.csv')
        ->and($callbackDisk)->toBe('local');
});

it('handles invalid file URL', function () {
    // Arrange
    $fileUrl = 'https://invalid-url-that-does-not-exist.com/test.csv';
    $action = new DownloadFile();

    // Act & Assert
    expect(fn () => $action->execute($fileUrl))
        ->toThrow(RuntimeException::class);
});
