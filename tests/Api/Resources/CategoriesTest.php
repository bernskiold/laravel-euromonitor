<?php

namespace Tests\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\Resources\Categories;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\Category;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Enum\Context;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(\Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient::class);
    $this->resource = new Categories($this->client);
});

it('makes correct API call and returns list response', function () {
    $this->resource->context = Context::StatisticsApi;

    $apiResponse = [
        (object) [
            'id' => 1,
            'name' => 'Test Category',
            'level' => 2,
            'industryCode' => 'TEST123',
            'industryName' => 'Test Industry',
            'industryOrder' => 1,
            'parentId' => 0,
            'parentName' => 'Parent Category',
        ],
    ];

    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/category', Mockery::type('array'))
        ->andReturn($apiResponse);

    $response = $this->resource->all();

    expect($response)
        ->toBeInstanceOf(ListResponse::class)
        ->and($response->totalRecords)->toBe(1)
        ->and($response->currentOffset)->toBe(0)
        ->and($response->get()->first())
        ->toBeInstanceOf(Category::class)
        ->and($response->get()->first()->name)->toBe('Test Category');
});

it('handles categories with null parent', function () {
    $this->resource->context = Context::StatisticsApi;

    $apiResponse = [
        (object) [
            'id' => 1,
            'name' => 'Test Category',
            'level' => 1,
            'industryCode' => 'TEST123',
            'industryName' => 'Test Industry',
            'industryOrder' => 1,
            'parentId' => null,
            'parentName' => null,
        ],
    ];

    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/category', Mockery::type('array'))
        ->andReturn($apiResponse);

    $response = $this->resource->all();

    expect($response->get()->first())
        ->toBeInstanceOf(Category::class)
        ->and($response->get()->first()->parentId)->toBeNull()
        ->and($response->get()->first()->parentName)->toBeNull();
});
