<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;

/**
 * @mixin EuromonitorResource
 */
trait SupportsLocalCurrency
{
    public bool $useLocalCurrency = false;

    public function useLocalCurrency(bool $useLocalCurrency = true): static
    {
        $this->useLocalCurrency = $useLocalCurrency;

        return $this;
    }

    public function buildQuerySupportsLocalCurrency(array $query): array
    {

        if ($this->useLocalCurrency) {
            $queryKey = $this->forMassExtraction() ? 'LocalCurrency' : 'localCurrency';
            $query[$queryKey] = true;
        }

        return $query;
    }
}
