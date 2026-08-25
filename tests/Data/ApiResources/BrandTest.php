<?php

namespace Tests\Data\ApiResources;

use Bernskiold\LaravelEuromonitor\Data\ApiResources\Brand;

it('can create from a catalog response', function () {
    $brand = Brand::fromResponse(sample('catalog/brand')[0]);

    expect($brand)
        ->brandId->toBe(903112)
        ->brandName->toBe('Lotus')
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->geographyId->toBe(21)
        ->geographyName->toBe('Belgium')
        ->industryCode->toBe('CT')
        ->industryName->toBe('Consumer Tissue')
        ->categoryId->toBe(11069)
        ->categoryName->toBe('Toilet Paper');
});

it('only requires the brand id and name', function () {
    $brand = Brand::fromResponse((object) [
        'brandId' => 903112,
        'brandName' => 'Lotus',
    ]);

    expect($brand)
        ->brandId->toBe(903112)
        ->brandName->toBe('Lotus')
        ->companyId->toBeNull()
        ->companyName->toBeNull()
        ->geographyId->toBeNull()
        ->categoryId->toBeNull();
});
