<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\Resources\Geographies;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\Geography;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(\Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient::class);
    $this->resource = new Geographies($this->client);
});

it('makes correct API call and returns list response', function () {
    $apiResponse = [
        (object) [
            'id' => 1,
            'name' => 'Test Geography',
            'isoAlpha2Code' => 'US',
            'parentId' => 0,
            'parentName' => 'World',
        ],
    ];

    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/geography')
        ->andReturn($apiResponse);

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(1)
        ->and($response->currentOffset)->toBe(0)
        ->and($response->get()->first())
        ->toBeInstanceOf(Geography::class)
        ->and($response->get()->first()->name)->toBe('Test Geography');
});

it('handles geographies with null parent', function () {
    $apiResponse = [
        (object) [
            'id' => 1,
            'name' => 'Test Geography',
            'isoAlpha2Code' => 'US',
            'parentId' => null,
            'parentName' => null,
        ],
    ];

    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/geography')
        ->andReturn($apiResponse);

    $response = $this->resource->all();

    expect($response->get()->first())
        ->toBeInstanceOf(Geography::class)
        ->and($response->get()->first()->parentId)->toBeNull()
        ->and($response->get()->first()->parentName)->toBeNull();
});
