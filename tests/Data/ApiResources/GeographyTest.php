<?php

namespace Tests\Unit\Data\ApiResources;

use Bernskiold\LaravelEuromonitor\Data\ApiResources\Geography;

it('can create from response', function () {
    $response = (object) [
        'id' => 1,
        'name' => 'Test Geography',
        'isoAlpha2Code' => 'US',
        'parentId' => 0,
        'parentName' => 'World',
    ];

    $geography = Geography::fromResponse($response);

    expect($geography)
        ->id->toBe(1)
        ->name->toBe('Test Geography')
        ->isoAlpha2Code->toBe('US')
        ->parentId->toBe(0)
        ->parentName->toBe('World');
});

it('can create with null parent', function () {
    $response = (object) [
        'id' => 1,
        'name' => 'Test Geography',
        'isoAlpha2Code' => 'US',
        'parentId' => null,
        'parentName' => null,
    ];

    $geography = Geography::fromResponse($response);

    expect($geography)
        ->parentId->toBeNull()
        ->parentName->toBeNull();
});
