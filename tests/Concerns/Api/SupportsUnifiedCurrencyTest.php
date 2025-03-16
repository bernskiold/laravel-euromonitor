<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsUnifiedCurrency;
use Bernskiold\LaravelEuromonitor\Enum\Currency;
use Mockery;

beforeEach(function () {
    $this->trait = new class() {
        use SupportsUnifiedCurrency;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets unified currency from enum', function () {
    $this->trait->unifiedCurrency(Currency::USD);
    expect($this->trait->unifiedCurrency)->toBe(Currency::USD);
});

it('sets unified currency from string', function () {
    $this->trait->unifiedCurrency('USD');
    expect($this->trait->unifiedCurrency)->toBe(Currency::USD);
});

it('sets USD currency', function () {
    $this->trait->inUSD();
    expect($this->trait->unifiedCurrency)->toBe(Currency::USD);
});

it('sets EUR currency', function () {
    $this->trait->inEUR();
    expect($this->trait->unifiedCurrency)->toBe(Currency::EUR);
});

it('sets GBP currency', function () {
    $this->trait->inGBP();
    expect($this->trait->unifiedCurrency)->toBe(Currency::GBP);
});

it('sets JPY currency', function () {
    $this->trait->inJPY();
    expect($this->trait->unifiedCurrency)->toBe(Currency::JPY);
});

it('sets CHF currency', function () {
    $this->trait->inCHF();
    expect($this->trait->unifiedCurrency)->toBe(Currency::CHF);
});

it('builds query with unified currency when mass extraction is used', function () {
    $this->trait->usesMassExtraction = true;
    $this->trait->unifiedCurrency = Currency::USD;

    $query = $this->trait->buildQuerySupportsUnifiedCurrency([]);

    expect($query)->toHaveKey('UnifiedCurrency')
        ->and($query['UnifiedCurrency'])->toBe(1);
});

it('builds query with unified currency when mass extraction is not used', function () {
    $this->trait->unifiedCurrency = Currency::USD;

    $query = $this->trait->buildQuerySupportsUnifiedCurrency([]);

    expect($query)->toHaveKey('unifiedCurrency')
        ->and($query['unifiedCurrency'])->toBe('USD');
});

it('builds query without unified currency when not set', function () {
    $this->trait->unifiedCurrency = null;

    $query = $this->trait->buildQuerySupportsUnifiedCurrency([]);

    expect($query)->toBe([]);
});
