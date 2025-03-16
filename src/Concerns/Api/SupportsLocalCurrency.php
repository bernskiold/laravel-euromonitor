<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

trait SupportsLocalCurrency
{
    public bool $useLocalCurrency = false;

    public function useLocalCurrency(bool $useLocalCurrency = true): static
    {
        $this->useLocalCurrency = $useLocalCurrency;

        return $this;
    }

    protected function buildQuerySupportsLocalCurrency(array $query): array
    {
        if ($this->useLocalCurrency) {
            $query['localCurrency'] = true;
        }

        return $query;
    }
}
