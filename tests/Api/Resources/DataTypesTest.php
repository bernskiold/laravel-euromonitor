<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\Resources\DataTypes;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\DataType;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Enum\MeasureType;
use Bernskiold\LaravelEuromonitor\Enum\UnitType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(\Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient::class);
    $this->resource = new DataTypes($this->client);
});

it('throws exception when measure type is not set', function () {
    expect(fn () => $this->resource->all())
        ->toThrow(InvalidEuromonitorQueryException::class);
});

it('makes correct API call and returns list response', function () {
    $this->resource->measureType(MeasureType::BrandShare);

    $apiResponse = [
        (object) [
            'id' => 1,
            'name' => 'Test Data Type',
        ],
    ];

    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/BrandShare/datatype', Mockery::type('array'))
        ->andReturn($apiResponse);

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(1)
        ->and($response->currentOffset)->toBe(0)
        ->and($response->get()->first())
        ->toBeInstanceOf(DataType::class)
        ->and($response->get()->first()->name)->toBe('Test Data Type');
});
