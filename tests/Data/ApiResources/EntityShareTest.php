<?php

namespace Tests\Data\ApiResources;

use Bernskiold\LaravelEuromonitor\Data\ApiResources\Datum;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\EntityShare;
use Illuminate\Support\Collection;

it('can create a brand share from a response', function () {
    $entityShare = EntityShare::fromResponse(sample('statistics/brandshare')->brandShares[0]);

    expect($entityShare)
        ->researchYear->toBe(2024)
        ->geographyId->toBe(21)
        ->geographyName->toBe('Belgium')
        ->categoryId->toBe(11069)
        ->categoryName->toBe('Toilet Paper')
        ->industryCode->toBe('CT')
        ->industryName->toBe('Consumer Tissue')
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->brandId->toBe(903112)
        ->brandName->toBe('Lotus')
        ->dataTypeId->toBe(107)
        ->dataTypeName->toBe('Retail Value RSP')
        ->shareTypeId->toBe(0)
        ->shareTypeName->toBe('Global Brand Owner')
        ->exchangeRateName->toBe('Fixed 2024 Exchange Rate')
        ->unit->toBe('% breakdown')
        ->unitMultiplier->toBe(1);

    expect($entityShare->data)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3);

    expect($entityShare->data->first())
        ->toBeInstanceOf(Datum::class)
        ->year->toBe(2022)
        ->value->toBe(21.4);
});

it('can create a company share from a response', function () {
    $entityShare = EntityShare::fromResponse(sample('statistics/companyshare')->companyShares[0]);

    expect($entityShare)
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->brandId->toBeNull()
        ->brandName->toBeNull();
});

it('resolves the entity to the brand on a brand share', function () {
    $entityShare = EntityShare::fromResponse(sample('statistics/brandshare')->brandShares[1]);

    expect($entityShare->entityId())->toBe(903118)
        ->and($entityShare->entityName())->toBe('Scott')
        ->and($entityShare->hasEntityId())->toBeTrue();
});

it('resolves the entity to the company on a company share', function () {
    $entityShare = EntityShare::fromResponse(sample('statistics/companyshare')->companyShares[1]);

    expect($entityShare->entityId())->toBe(674614)
        ->and($entityShare->entityName())->toBe('Kimberly-Clark Corp');
});

it('creates empty collection when no data provided', function () {
    $entityShare = EntityShare::fromResponse((object) [
        'researchYear' => 2024,
        'companyName' => 'Essity AB',
    ]);

    expect($entityShare->data)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(0);
});

it('tolerates a response without identifiers', function () {
    $entityShare = EntityShare::fromResponse((object) [
        'researchYear' => 2024,
        'companyName' => 'Essity AB',
        'brandName' => 'Lotus',
    ]);

    expect($entityShare->brandId)->toBeNull()
        ->and($entityShare->companyId)->toBeNull()
        ->and($entityShare->hasEntityId())->toBeFalse()
        ->and($entityShare->entityName())->toBe('Lotus');
});

it('can create from a mass extraction file row', function () {
    $entityShare = EntityShare::fromFile(sample('extract/brandshare')[0]);

    expect($entityShare)
        ->researchYear->toBe(2024)
        ->geographyId->toBe(21)
        ->geographyName->toBe('Belgium')
        ->categoryId->toBe(11069)
        ->industryCode->toBe('CT')
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB')
        ->brandId->toBe(903112)
        ->brandName->toBe('Lotus')
        ->dataTypeId->toBe(107)
        ->shareTypeId->toBe(0)
        ->unit->toBe('% breakdown')
        ->unitMultiplier->toBe(1);
});

it('reads the year columns of a file row as data points', function () {
    $entityShare = EntityShare::fromFile(sample('extract/brandshare')[0]);

    expect($entityShare->data)->toHaveCount(3);

    $years = $entityShare->data->map(fn (Datum $datum) => $datum->year)->values()->all();
    $values = $entityShare->data->map(fn (Datum $datum) => $datum->value)->values()->all();

    expect($years)->toBe([2022, 2023, 2024])
        ->and($values)->toBe([21.4, 22.1, 22.8]);
});

it('tolerates a legacy file row without identifiers', function () {
    $entityShare = EntityShare::fromFile(sample('extract/brandshare-legacy')[0]);

    expect($entityShare->brandName)->toBe('Lotus')
        ->and($entityShare->brandId)->toBeNull()
        ->and($entityShare->companyId)->toBeNull()
        ->and($entityShare->hasEntityId())->toBeFalse()
        ->and($entityShare->data)->toHaveCount(2);
});
