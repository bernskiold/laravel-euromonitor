<?php

use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Exceptions\EuromonitorApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->apiKey = 'test-api-key';
    $this->baseUrl = 'https://api.euromonitor.com';
    $this->version = '1.0';
    $this->client = new EuromonitorApiClient(
        apiKey: $this->apiKey,
        baseUrl: $this->baseUrl,
        version: $this->version,
    );

    // Mock config values
    config([
        'services.euromonitor.username' => 'test-username',
        'services.euromonitor.password' => 'test-password',
    ]);
});

it('can get an access token', function () {
    Http::fake([
        '*/authentication/connect/token' => Http::response([
            'access_token' => 'test-token',
            'expires_in' => 3600,
        ], 200),
    ]);

    $token = $this->client->getToken();

    expect($token)->toBeObject()
        ->and($token->access_token)->toBe('test-token')
        ->and($token->expires_in)->toBe(3600);
});

it('throws exception when token request fails', function () {
    Http::fake([
        '*/authentication/connect/token' => Http::response([], 401),
    ]);

    expect(fn () => $this->client->getToken())
        ->toThrow(EuromonitorApiException::class);
});
