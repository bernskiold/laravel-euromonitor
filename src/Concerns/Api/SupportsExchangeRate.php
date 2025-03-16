<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Enum\ExchangeRate;

use function is_null;
use function property_exists;

trait SupportsExchangeRate
{
    public ?ExchangeRate $exchangeRate = null;

    public function exchangeRate(ExchangeRate|string $exchangeRate): static
    {
        $this->exchangeRate = $exchangeRate instanceof ExchangeRate ? $exchangeRate : ExchangeRate::tryFrom($exchangeRate);

        return $this;
    }

    protected function buildQuerySupportsExchangeRate(array $query): array
    {
        if (is_null($this->exchangeRate)) {
            return $query;
        }

        if (property_exists($this, 'usesMassExtraction') && $this->usesMassExtraction) {
            $query['ExchangeRate'] = $this->exchangeRate->code();
        } else {
            $query['exchangeRate'] = $this->exchangeRate->value;
        }

        return $query;
    }
}
