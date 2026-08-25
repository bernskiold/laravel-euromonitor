<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsPerCapita;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource {
        use SupportsPerCapita;
    };
});

afterEach(function () {
    Mockery::close();
});

it('builds query with perCapita flag', function () {
    $this->trait->perCapitaInsteadOfCountryTotal = true;

    $query = $this->trait->buildQuerySupportsPerCapita([]);

    expect($query)->toHaveKey('perCapitaId')
        ->and($query['perCapitaId'])->toBe(1);
});

it('builds query without perCapita flag', function () {
    $this->trait->perCapitaInsteadOfCountryTotal = false;

    $query = $this->trait->buildQuerySupportsPerCapita([]);

    expect($query)->not->toHaveKey('perCapitaId');
});
