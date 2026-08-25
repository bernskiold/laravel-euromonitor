<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsContext;
use Bernskiold\LaravelEuromonitor\Enum\Context;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource {
        use SupportsContext;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets context from enum', function () {
    $this->trait->context(Context::StatisticsApi);
    expect($this->trait->context)->toBe(Context::StatisticsApi);
});

it('sets context from string', function () {
    $this->trait->context('Statistics');
    expect($this->trait->context)->toBe(Context::StatisticsApi);
});

it('sets context for statistics API', function () {
    $this->trait->forStatisticsApi();
    expect($this->trait->context)->toBe(Context::StatisticsApi);
});

it('sets context for analysis API', function () {
    $this->trait->forAnalysisApi();
    expect($this->trait->context)->toBe(Context::AnalysisApi);
});


it('builds query with valid context', function () {
    $this->trait->context = Context::StatisticsApi;

    $query = $this->trait->buildQuerySupportsContext([]);

    expect($query)->toHaveKey('context')
        ->and($query['context'])->toBe(Context::StatisticsApi->value);
});
