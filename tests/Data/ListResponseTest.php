<?php

use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Illuminate\Support\Collection;
use ReflectionProperty;

it('can create a list response with array data', function () {
    $data = ['item1', 'item2', 'item3'];
    $response = new ListResponse(
        data: $data,
        totalRecords: 3,
        currentOffset: 0
    );

    expect($response->get())->toBeInstanceOf(Collection::class)
        ->and($response->get()->toArray())->toBe($data)
        ->and($response->totalRecords)->toBe(3)
        ->and($response->currentOffset)->toBe(0);
});

it('can create a list response with collection data', function () {
    $collection = new Collection(['item1', 'item2']);
    $response = new ListResponse(
        data: $collection,
        totalRecords: 2,
        currentOffset: 0
    );

    expect($response->get())->toBe($collection)
        ->and($response->totalRecords)->toBe(2)
        ->and($response->currentOffset)->toBe(0);
});

it('can create an empty list response', function () {
    $response = new ListResponse();

    expect($response->get())->toBeInstanceOf(Collection::class)
        ->and($response->get()->isEmpty())->toBeTrue()
        ->and($response->totalRecords)->toBe(0)
        ->and($response->currentOffset)->toBe(0);
});

it('has readonly properties', function () {
    $response = new ListResponse(['item1']);

    $totalRecordsProperty = new ReflectionProperty($response, 'totalRecords');
    $currentOffsetProperty = new ReflectionProperty($response, 'currentOffset');

    expect($totalRecordsProperty->isReadOnly())->toBeTrue()
        ->and($currentOffsetProperty->isReadOnly())->toBeTrue();
});
