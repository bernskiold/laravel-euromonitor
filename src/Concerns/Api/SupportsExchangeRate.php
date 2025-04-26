<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Enum\ExchangeRate;

use function is_null;
use function property_exists;

/**
 * @mixin EuromonitorResource
 */
trait SupportsExchangeRate
{
    public ?ExchangeRate $exchangeRate = null;

    public function exchangeRate(ExchangeRate|string $exchangeRate): static
    {
        $this->exchangeRate = $exchangeRate instanceof ExchangeRate ? $exchangeRate : ExchangeRate::tryFrom($exchangeRate);

        return $this;
    }

    public function buildQuerySupportsExchangeRate(array $query): array
    {
        if (is_null($this->exchangeRate)) {
            return $query;
        }

        if ($this->forMassExtraction()) {
            $query['ExchangeRate'] = $this->exchangeRate->code();
        } else {
            $query['exchangeRate'] = $this->exchangeRate->value;
        }

        return $query;
    }
}
