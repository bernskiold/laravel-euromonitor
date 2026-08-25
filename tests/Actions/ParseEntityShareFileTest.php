<?php

namespace Tests\Actions;

use Bernskiold\LaravelEuromonitor\Actions\ParseEntityShareFile;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\EntityShare;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('euromonitor');
    $this->action = new ParseEntityShareFile;
});

it('parses a downloaded brand share extract into entity shares', function () {
    Storage::disk('euromonitor')->put('extract.json', sampleContents('extract/brandshare'));

    $shares = $this->action->execute('euromonitor', 'extract.json');

    expect($shares)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->and($shares->first())->toBeInstanceOf(EntityShare::class);
});

it('carries the euromonitor identifiers through from the file', function () {
    Storage::disk('euromonitor')->put('extract.json', sampleContents('extract/brandshare'));

    $shares = $this->action->execute('euromonitor', 'extract.json');

    expect($shares->first())
        ->brandId->toBe(903112)
        ->brandName->toBe('Lotus')
        ->companyId->toBe(674613)
        ->companyName->toBe('Essity AB');

    expect($shares->pluck('brandId')->all())->toBe([903112, 903118]);
});

it('parses the year columns into data points', function () {
    Storage::disk('euromonitor')->put('extract.json', sampleContents('extract/brandshare'));

    $shares = $this->action->execute('euromonitor', 'extract.json');

    expect($shares->first()->data)->toHaveCount(3)
        ->and($shares->first()->data->last()->year)->toBe(2024)
        ->and($shares->first()->data->last()->value)->toBe(22.8);
});

it('parses a legacy extract without identifiers', function () {
    Storage::disk('euromonitor')->put('extract.json', sampleContents('extract/brandshare-legacy'));

    $shares = $this->action->execute('euromonitor', 'extract.json');

    expect($shares)->toHaveCount(1)
        ->and($shares->first()->brandId)->toBeNull()
        ->and($shares->first()->brandName)->toBe('Lotus');
});

it('returns an empty collection for an empty extract', function () {
    Storage::disk('euromonitor')->put('extract.json', '[]');

    expect($this->action->execute('euromonitor', 'extract.json'))->toHaveCount(0);
});
