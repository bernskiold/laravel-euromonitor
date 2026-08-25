<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsLocalCurrency;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource {
        use SupportsLocalCurrency;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets local currency flag', function () {
    $this->trait->useLocalCurrency();
    expect($this->trait->useLocalCurrency)->toBeTrue();

    $this->trait->useLocalCurrency(false);
    expect($this->trait->useLocalCurrency)->toBeFalse();
});


it('builds query with localCurrency flag', function () {
    $this->trait->useLocalCurrency();

    $query = $this->trait->buildQuerySupportsLocalCurrency([]);

    expect($query)->toHaveKey('localCurrency')
        ->and($query['localCurrency'])->toBe(true);
});

it('builds query without localCurrency flag', function () {
    $this->trait->useLocalCurrency(false);

    $query = $this->trait->buildQuerySupportsLocalCurrency([]);

    expect($query)
        ->not->toHaveKey('localCurrency');
});
