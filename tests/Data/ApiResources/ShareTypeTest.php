<?php

namespace Tests\Unit\Data\ApiResources;

use Bernskiold\LaravelEuromonitor\Data\ApiResources\ShareType;

it('can create from response', function () {
    $response = (object) [
        'id' => 1,
        'name' => 'Test Share Type',
        'definition' => 'This is a test definition',
    ];

    $shareType = ShareType::fromResponse($response);

    expect($shareType)
        ->id->toBe(1)
        ->name->toBe('Test Share Type')
        ->definition->toBe('This is a test definition');
});
