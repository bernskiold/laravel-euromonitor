<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsInflationType;
use Bernskiold\LaravelEuromonitor\Enum\InflationType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource {
        use SupportsInflationType;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets inflation type from enum', function () {
    $this->trait->inflationType(InflationType::Current);
    expect($this->trait->inflationType)->toBe(InflationType::Current);
});

it('sets inflation type from string', function () {
    $this->trait->inflationType('Current');
    expect($this->trait->inflationType)->toBe(InflationType::Current);
});


it('builds query with valid inflationType', function () {
    $this->trait->inflationType = InflationType::Current;

    $query = $this->trait->buildQuerySupportsInflationType([]);

    expect($query)->toHaveKey('inflationType')
        ->and($query['inflationType'])->toBe(InflationType::Current->value);
});
