<?php

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->client = Mockery::mock(\Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient::class);
    $this->resource = new EuromonitorResource($this->client);
});

afterEach(function () {
    Mockery::close();
});

it('can build an empty query by default', function () {
    $query = $this->resource->buildQuery();
    expect($query)->toBe([]);
});

it('validates query with custom rules', function () {
    $resource = new class($this->client) extends EuromonitorResource {
        protected function getRules(): array
        {
            return [
                'required_field' => 'required|string',
            ];
        }
    };

    expect(fn () => $resource->buildQuery())
        ->toThrow(InvalidEuromonitorQueryException::class);
});
