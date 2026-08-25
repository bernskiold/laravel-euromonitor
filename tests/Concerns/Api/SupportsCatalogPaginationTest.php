<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsCatalogPagination;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;

beforeEach(function () {
    $this->trait = new class extends FakeResource
    {
        use SupportsCatalogPagination;
    };
});

it('defaults to the first page and the maximum page size', function () {
    $query = $this->trait->buildQuerySupportsCatalogPagination([]);

    expect($query['PageNumber'])->toBe(1)
        ->and($query['NoOfRecords'])->toBe(10_000);
});

it('sets the page number', function () {
    $this->trait->page(4);

    expect($this->trait->currentPage)->toBe(4)
        ->and($this->trait->buildQuerySupportsCatalogPagination([])['PageNumber'])->toBe(4);
});

it('sets the page size', function () {
    $this->trait->perPage(250);

    expect($this->trait->perPage)->toBe(250)
        ->and($this->trait->buildQuerySupportsCatalogPagination([])['NoOfRecords'])->toBe(250);
});

it('is chainable', function () {
    $result = $this->trait->page(2)->perPage(100);

    expect($result)->toBe($this->trait);
});

it('throws when the page size exceeds the documented maximum', function () {
    $this->trait->perPage(10_001);

    expect(fn () => $this->trait->buildQuerySupportsCatalogPagination([]))
        ->toThrow(InvalidEuromonitorQueryException::class, 'The maximum number of records per page is 10,000.');
});

it('allows exactly the documented maximum', function () {
    $this->trait->perPage(10_000);

    expect($this->trait->buildQuerySupportsCatalogPagination([])['NoOfRecords'])->toBe(10_000);
});

it('throws when the page number is below one', function () {
    $this->trait->page(0);

    expect(fn () => $this->trait->buildQuerySupportsCatalogPagination([]))
        ->toThrow(InvalidEuromonitorQueryException::class, 'The page number starts at 1.');
});

it('preserves keys already in the query', function () {
    $query = $this->trait->buildQuerySupportsCatalogPagination(['GeographyIds' => [21]]);

    expect($query)->toHaveKeys(['GeographyIds', 'PageNumber', 'NoOfRecords']);
});
