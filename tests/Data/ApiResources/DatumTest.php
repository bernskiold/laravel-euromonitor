<?php

use Bernskiold\LaravelEuromonitor\Data\ApiResources\Datum;

it('can create from response', function () {
    $response = (object) [
        'year' => 2023,
        'value' => 123.45,
    ];

    $datum = Datum::fromResponse($response);

    expect($datum->year)->toBe(2023);
    expect($datum->value)->toBe(123.45);
});
