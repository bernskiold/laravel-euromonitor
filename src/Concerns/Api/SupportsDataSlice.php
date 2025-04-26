<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;

/**
 * @mixin EuromonitorResource
 */
trait SupportsDataSlice
{
    public int $dataSlice = 1;

    public function dataSlice(int|string $dataSlice): static
    {
        $this->dataSlice = $dataSlice;

        return $this;
    }

    public function allProducts(): static
    {
        $this->dataSlice = 1;

        return $this;
    }

    public function lowestLevelProducts(): static
    {
        $this->dataSlice = 2;

        return $this;
    }

    public function buildQuerySupportsDataSlice(array $query): array
    {
        $queryKey = $this->forMassExtraction() ? 'DataSliceId' : 'DataSliceID';

        $query[$queryKey] = $this->dataSlice;

        return $query;
    }
}
