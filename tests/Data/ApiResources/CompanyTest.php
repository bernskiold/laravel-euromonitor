<?php

namespace Tests\Data\ApiResources;

use Bernskiold\LaravelEuromonitor\Data\ApiResources\Company;

it('can create from a catalog response', function () {
    $company = Company::fromResponse(sample('catalog/company')[0]);

    expect($company)
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->geographyId->toBe(21)
        ->geographyName->toBe('Belgium')
        ->industryCode->toBe('CT')
        ->industryName->toBe('Consumer Tissue')
        ->categoryId->toBe(11069)
        ->categoryName->toBe('Toilet Paper');
});

it('only requires the company id and name', function () {
    $company = Company::fromResponse((object) [
        'companyId' => 674613,
        'companyName' => 'Essity AB',
    ]);

    expect($company)
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->geographyId->toBeNull()
        ->categoryId->toBeNull();
});
