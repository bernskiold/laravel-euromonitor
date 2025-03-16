<?php

use Bernskiold\LaravelEuromonitor\Data\JobDownloadResponse;
use ReflectionProperty;

it('can create a job download response with all properties', function () {
    $response = new JobDownloadResponse(
        url: 'https://example.com/download',
        message: 'Download ready',
        status: 'completed'
    );

    expect($response->url)->toBe('https://example.com/download')
        ->and($response->message)->toBe('Download ready')
        ->and($response->status)->toBe('completed');
});

it('can create a job download response with null properties', function () {
    $response = new JobDownloadResponse(
        url: null,
        message: null,
        status: null
    );

    expect($response->url)->toBeNull()
        ->and($response->message)->toBeNull()
        ->and($response->status)->toBeNull();
});

it('has readonly properties', function () {
    $response = new JobDownloadResponse(
        url: 'https://example.com/download',
        message: 'Download ready',
        status: 'completed'
    );

    $urlProperty = new ReflectionProperty($response, 'url');
    $messageProperty = new ReflectionProperty($response, 'message');
    $statusProperty = new ReflectionProperty($response, 'status');

    expect($urlProperty->isReadOnly())->toBeTrue()
        ->and($messageProperty->isReadOnly())->toBeTrue()
        ->and($statusProperty->isReadOnly())->toBeTrue();
});
