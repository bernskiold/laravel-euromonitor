<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsExchangeRate;
use Bernskiold\LaravelEuromonitor\Enum\ExchangeRate;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class() {
        use SupportsExchangeRate;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets exchange rate from enum', function () {
    $this->trait->exchangeRate(ExchangeRate::Fixed);
    expect($this->trait->exchangeRate)->toBe(ExchangeRate::Fixed);
});

it('sets exchange rate from string', function () {
    $this->trait->exchangeRate('Fixed');
    expect($this->trait->exchangeRate)->toBe(ExchangeRate::Fixed);
});

it('builds query with exchangeRate code when using mass extraction', function () {
    $this->trait->exchangeRate = ExchangeRate::Fixed;
    $this->trait->usesMassExtraction = true;

    $query = $this->trait->buildQuerySupportsExchangeRate([]);

    expect($query)->toHaveKey('ExchangeRate')
        ->and($query['ExchangeRate'])->toBe(ExchangeRate::Fixed->code());
});

it('builds query with exchangeRate value when not using mass extraction', function () {
    $this->trait->exchangeRate = ExchangeRate::Fixed;
    $this->trait->usesMassExtraction = false;

    $query = $this->trait->buildQuerySupportsExchangeRate([]);

    expect($query)->toHaveKey('exchangeRate')
        ->and($query['exchangeRate'])->toBe(ExchangeRate::Fixed->value);
});

it('builds query without exchangeRate when null', function () {
    $this->trait->exchangeRate = null;

    $query = $this->trait->buildQuerySupportsExchangeRate([]);

    expect($query)->not->toHaveKey('exchangeRate')
        ->and($query)->not->toHaveKey('ExchangeRate');
});
