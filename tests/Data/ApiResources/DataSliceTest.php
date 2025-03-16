<?php

use Bernskiold\LaravelEuromonitor\Data\ApiResources\DataSlice;

it('can create from response', function () {
    $response = (object) [
        'id' => 1,
        'name' => 'Test Slice',
        'industryCode' => 'TEST123',
        'industryName' => 'Test Industry',
    ];

    $slice = DataSlice::fromResponse($response);

    expect($slice->id)->toBe(1);
    expect($slice->name)->toBe('Test Slice');
    expect($slice->industryCode)->toBe('TEST123');
    expect($slice->industryName)->toBe('Test Industry');
});
