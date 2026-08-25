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

it('maps the documented category hierarchy', function () {
    $this->resource->context = Context::StatisticsApi;

    $this->client->shouldReceive('get')
        ->once()
        ->with('/catalog/category', Mockery::type('array'))
        ->andReturn(sample('catalog/category'));

    $categories = $this->resource->all()->get();

    expect($categories)->toHaveCount(4);

    $root = $categories->firstWhere('id', 1);
    $child = $categories->firstWhere('id', 10);
    $grandchild = $categories->firstWhere('id', 101);

    expect($root)
        ->name->toBe('Tissue and Hygiene')
        ->level->toBe(0)
        ->parentId->toBeNull()
        ->industryCode->toBe('DPP');

    expect($child)
        ->level->toBe(1)
        ->parentId->toBe(1)
        ->parentName->toBe('Tissue and Hygiene');

    expect($grandchild)
        ->level->toBe(2)
        ->parentId->toBe(10);
});
