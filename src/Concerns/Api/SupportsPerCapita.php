<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

trait SupportsPerCapita
{
    public bool $perCapitaInsteadOfCountryTotal = false;

    public function perCapitaInsteadOfCountryTotal(bool $perCapitaInsteadOfCountryTotal = true): self
    {
        $this->perCapitaInsteadOfCountryTotal = $perCapitaInsteadOfCountryTotal;

        return $this;
    }

    public function buildQuerySupportsPerCapita(array $query): array
    {
        if ($this->perCapitaInsteadOfCountryTotal) {
            $query['perCapitaId'] = 1;
        }

        return $query;
    }
}
