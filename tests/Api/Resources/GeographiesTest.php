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

it('maps the documented geography hierarchy', function () {
    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/geography')
        ->andReturn(sample('catalog/geography'));

    $geographies = $this->resource->all()->get();

    expect($geographies)->toHaveCount(4);

    $world = $geographies->firstWhere('id', 1);
    $belgium = $geographies->firstWhere('id', 21);
    $liege = $geographies->firstWhere('id', 211);

    expect($world)
        ->name->toBe('World')
        ->parentId->toBeNull()
        ->isoAlpha2Code->toBeNull();

    expect($belgium)
        ->name->toBe('Belgium')
        ->parentId->toBe(2)
        ->parentName->toBe('Western Europe')
        ->isoAlpha2Code->toBe('BE');

    expect($liege)
        ->parentId->toBe(21)
        ->isoAlpha2Code->toBeNull();
});
