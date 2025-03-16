<?php

namespace Tests\Unit\Data\ApiResources;

use Bernskiold\LaravelEuromonitor\Data\ApiResources\Datum;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\EntityShare;
use Illuminate\Support\Collection;

it('can create from response', function () {
    $response = (object) [
        'researchYear' => 2023,
        'geographyId' => 1,
        'geographyName' => 'Test Geography',
        'categoryId' => 2,
        'categoryName' => 'Test Category',
        'industryCode' => 'TEST123',
        'industryName' => 'Test Industry',
        'entityName' => 'Test Entity',
        'dataTypeId' => 3,
        'dataTypeName' => 'Test Data Type',
        'shareTypeId' => 4,
        'shareTypeName' => 'Test Share Type',
        'exchangeRateName' => 'Test Exchange Rate',
        'unit' => 'USD',
        'unitMultiplier' => 1,
        'data' => [
            (object) ['year' => 2023, 'value' => 123.45],
            (object) ['year' => 2022, 'value' => 100.00],
        ],
    ];

    $entityShare = EntityShare::fromResponse($response);

    expect($entityShare)
        ->researchYear->toBe(2023)
        ->geographyId->toBe(1)
        ->geographyName->toBe('Test Geography')
        ->categoryId->toBe(2)
        ->categoryName->toBe('Test Category')
        ->industryCode->toBe('TEST123')
        ->industryName->toBe('Test Industry')
        ->entityName->toBe('Test Entity')
        ->dataTypeId->toBe(3)
        ->dataTypeName->toBe('Test Data Type')
        ->shareTypeId->toBe(4)
        ->shareTypeName->toBe('Test Share Type')
        ->exchangeRateName->toBe('Test Exchange Rate')
        ->unit->toBe('USD')
        ->unitMultiplier->toBe(1);

    expect($entityShare->data)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2);

    $firstDatum = $entityShare->data->first();
    expect($firstDatum)
        ->toBeInstanceOf(Datum::class)
        ->year->toBe(2023)
        ->value->toBe(123.45);
});

it('creates empty collection when no data provided', function () {
    $response = (object) [
        'researchYear' => 2023,
        'geographyId' => 1,
        'geographyName' => 'Test Geography',
        'categoryId' => 2,
        'categoryName' => 'Test Category',
        'industryCode' => 'TEST123',
        'industryName' => 'Test Industry',
        'entityName' => 'Test Entity',
        'dataTypeId' => 3,
        'dataTypeName' => 'Test Data Type',
        'shareTypeId' => 4,
        'shareTypeName' => 'Test Share Type',
        'exchangeRateName' => 'Test Exchange Rate',
        'unit' => 'USD',
        'unitMultiplier' => 1,
    ];

    $entityShare = EntityShare::fromResponse($response);

    expect($entityShare->data)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(0);
});
