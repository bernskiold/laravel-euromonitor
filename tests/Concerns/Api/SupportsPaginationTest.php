<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsPagination;
use Mockery;

beforeEach(function () {
    $this->trait = new class() {
        use SupportsPagination;
    };
});

afterEach(function () {
    Mockery::close();
});

it('builds query with default pagination values', function () {
    $query = $this->trait->buildQuerySupportsPagination([]);

    expect($query)->toHaveKey('offset')
        ->and($query)->toHaveKey('limit')
        ->and($query['offset'])->toBe(0)
        ->and($query['limit'])->toBe(1_000);
});

it('builds query with custom pagination values', function () {
    $this->trait->currentPage = 1;
    $this->trait->perPage = 25;

    $query = $this->trait->buildQuerySupportsPagination([]);

    expect($query)->toHaveKey('offset')
        ->and($query)->toHaveKey('limit')
        ->and($query['offset'])->toBe(25)
        ->and($query['limit'])->toBe(25);
});
