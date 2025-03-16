<?php

use Bernskiold\LaravelEuromonitor\Data\ApiResources\DataType;

it('can create from response', function () {
    $response = (object) [
        'id' => 1,
        'name' => 'Test Data Type',
    ];

    $dataType = DataType::fromResponse($response);

    expect($dataType->id)->toBe(1);
    expect($dataType->name)->toBe('Test Data Type');
});
