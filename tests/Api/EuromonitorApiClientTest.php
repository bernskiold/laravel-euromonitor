<?php

namespace Tests\Api;

use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Exceptions\EuromonitorApiException;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->apiKey = 'test-api-key';
    $this->baseUrl = 'https://api.euromonitor.com';
    $this->version = '1.0';
    $this->client = new EuromonitorApiClient(
        username: 'test-username',
        password: 'test-password',
        apiKey: $this->apiKey,
        baseUrl: $this->baseUrl,
        version: $this->version,
    );

    Cache::flush();
});

function fakeToken(string $token = 'test-token', int $expiresIn = 3600): void
{
    Http::fake([
        '*/authentication/connect/token' => Http::response([
            'access_token' => $token,
            'expires_in' => $expiresIn,
        ], 200),
        '*' => Http::response([], 200),
    ]);
}

it('can get an access token', function () {
    fakeToken();

    $token = $this->client->getToken();

    expect($token)->toBeObject()
        ->and($token->access_token)->toBe('test-token')
        ->and($token->expires_in)->toBe(3600);
});

it('requests the token as a password grant with the subscription key', function () {
    fakeToken();

    $this->client->getToken();

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.euromonitor.com/authentication/connect/token'
        && $request['grant_type'] === 'password'
        && $request['username'] === 'test-username'
        && $request['password'] === 'test-password'
        && $request->header('Ocp-Apim-Subscription-Key') === [$this->apiKey]
        && $request->header('Accept') === ['application/json; api-version=1.0']);
});

it('throws exception when token request fails', function () {
    Http::fake([
        '*/authentication/connect/token' => Http::response([], 401),
    ]);

    expect(fn () => $this->client->getToken())
        ->toThrow(EuromonitorApiException::class);
});

it('caches the access token for its lifetime', function () {
    fakeToken();

    $this->client->authorize();

    expect(Cache::get('euromonitor.access_token'))->toBe('test-token');
});

it('reuses a cached access token instead of requesting a new one', function () {
    Cache::put('euromonitor.access_token', 'cached-token', now()->addHour());

    Http::fake(['*' => Http::response([], 200)]);

    $this->client->get('/catalog/geography');

    Http::assertNotSent(fn (Request $request) => str_contains($request->url(), 'connect/token'));
    Http::assertSent(fn (Request $request) => $request->header('Authorization') === ['Bearer cached-token']);
});

it('sends the subscription key and versioned accept header on get requests', function () {
    Cache::put('euromonitor.access_token', 'cached-token', now()->addHour());
    Http::fake(['*' => Http::response(sampleContents('catalog/geography'), 200, ['Content-Type' => 'application/json'])]);

    $this->client->get('/catalog/geography');

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.euromonitor.com/catalog/geography'
        && $request->method() === 'GET'
        && $request->header('Ocp-Apim-Subscription-Key') === [$this->apiKey]
        && $request->header('Accept') === ['application/json; api-version=1.0']);
});

it('passes query parameters on get requests', function () {
    Cache::put('euromonitor.access_token', 'cached-token', now()->addHour());
    Http::fake(['*' => Http::response(sampleContents('catalog/brand'), 200, ['Content-Type' => 'application/json'])]);

    $this->client->get('/catalog/brand', ['PageNumber' => 2, 'NoOfRecords' => 500]);

    Http::assertSent(fn (Request $request) => str_contains($request->url(), 'PageNumber=2')
        && str_contains($request->url(), 'NoOfRecords=500'));
});

it('decodes a get response into objects', function () {
    Cache::put('euromonitor.access_token', 'cached-token', now()->addHour());
    Http::fake(['*' => Http::response(sampleContents('catalog/brand'), 200, ['Content-Type' => 'application/json'])]);

    $response = $this->client->get('/catalog/brand');

    expect($response)->toBeArray()
        ->and($response[0]->brandId)->toBe(903112);
});

it('posts a json body', function () {
    Cache::put('euromonitor.access_token', 'cached-token', now()->addHour());
    Http::fake(['*' => Http::response(sampleContents('statistics/brandshare'), 200, ['Content-Type' => 'application/json'])]);

    $response = $this->client->post('/statistics/brandshare/Percentage', [
        'limit' => 1000,
        'geographyIds' => [21],
        'brandIds' => [903112],
    ]);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request['limit'] === 1000
        && $request['brandIds'] === [903112]);

    expect($response->total)->toBe(2);
});

it('throws a request exception on an unsuccessful get', function () {
    Cache::put('euromonitor.access_token', 'cached-token', now()->addHour());
    Http::fake(['*' => Http::response(['responseStatus' => ['message' => 'Nope']], 400)]);

    expect(fn () => $this->client->get('/catalog/brand'))
        ->toThrow(RequestException::class);
});

it('throws a request exception on an unsuccessful post', function () {
    Cache::put('euromonitor.access_token', 'cached-token', now()->addHour());
    Http::fake(['*' => Http::response([], 500)]);

    expect(fn () => $this->client->post('/statistics/brandshare/Percentage', []))
        ->toThrow(RequestException::class);
});

it('can be built from a configuration array', function () {
    $client = EuromonitorApiClient::fromConfig([
        'username' => 'user',
        'password' => 'pass',
        'api_key' => 'key',
        'base_url' => 'https://api.euromonitor.com',
        'version' => '1.0',
    ]);

    expect($client)->toBeInstanceOf(EuromonitorApiClient::class);
});

it('does not double the slash between base url and endpoint', function () {
    Cache::put('euromonitor.access_token', 'cached-token', now()->addHour());
    Http::fake(['*' => Http::response('[]', 200, ['Content-Type' => 'application/json'])]);

    $this->client->get('/catalog/brand');

    Http::assertSent(fn (Request $request) => ! str_contains($request->url(), 'euromonitor.com//'));
});

it('tolerates an endpoint without a leading slash', function () {
    Cache::put('euromonitor.access_token', 'cached-token', now()->addHour());
    Http::fake(['*' => Http::response('[]', 200, ['Content-Type' => 'application/json'])]);

    $this->client->get('catalog/brand');

    Http::assertSent(fn (Request $request) => str_starts_with($request->url(), 'https://api.euromonitor.com/catalog/brand'));
});
