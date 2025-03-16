<?php

use Bernskiold\LaravelEuromonitor\Data\ApiResources\Datum;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\MarketSize;
use Illuminate\Support\Collection;

it('can create from response', function () {
    $response = (object) [
        'researchYear' => 2023,
        'geographyId' => 1,
        'geographyName' => 'Test Geography',
        'categoryId' => 2,
        'categoryName' => 'Test Category',
        'industry' => 'Test Industry',
        'dataTypeId' => 3,
        'dataType' => 'Test Data Type',
        'unitName' => 'USD',
        'inflationType' => 'Current',
        'exchangeRateName' => 'Test Exchange Rate',
        'perCapitaName' => 'Test Per Capita',
        'unitMultiplier' => 1,
        'data' => [
            (object) ['year' => 2023, 'value' => 123.45],
            (object) ['year' => 2022, 'value' => 100.00],
        ],
    ];

    $marketSize = MarketSize::fromResponse($response);

    expect($marketSize->researchYear)->toBe(2023)
        ->and($marketSize->geographyId)->toBe(1)
        ->and($marketSize->geographyName)->toBe('Test Geography')
        ->and($marketSize->categoryId)->toBe(2)
        ->and($marketSize->categoryName)->toBe('Test Category')
        ->and($marketSize->industry)->toBe('Test Industry')
        ->and($marketSize->dataTypeId)->toBe(3)
        ->and($marketSize->dataType)->toBe('Test Data Type')
        ->and($marketSize->unitName)->toBe('USD')
        ->and($marketSize->inflationType)->toBe('Current')
        ->and($marketSize->exchangeRateName)->toBe('Test Exchange Rate')
        ->and($marketSize->perCapitaName)->toBe('Test Per Capita')
        ->and($marketSize->unitMultiplier)->toBe(1)
        ->and($marketSize->data)->toBeInstanceOf(Collection::class)
        ->and($marketSize->data)->toHaveCount(2);

    $firstDatum = $marketSize->data->first();
    expect($firstDatum)->toBeInstanceOf(Datum::class)
        ->and($firstDatum->year)->toBe(2023)
        ->and($firstDatum->value)->toBe(123.45);
});

it('creates empty collection when no data provided', function () {
    $response = (object) [
        'researchYear' => 2023,
        'geographyId' => 1,
        'geographyName' => 'Test Geography',
        'categoryId' => 2,
        'categoryName' => 'Test Category',
        'industry' => 'Test Industry',
        'dataTypeId' => 3,
        'dataType' => 'Test Data Type',
        'unitName' => 'USD',
        'inflationType' => 'Current',
        'exchangeRateName' => 'Test Exchange Rate',
        'perCapitaName' => 'Test Per Capita',
        'unitMultiplier' => 1,
    ];

    $marketSize = MarketSize::fromResponse($response);

    expect($marketSize->data)->toBeInstanceOf(Collection::class)
        ->and($marketSize->data)->toHaveCount(0);
});
