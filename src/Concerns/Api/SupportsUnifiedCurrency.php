<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Enum\Currency;

use function is_null;
use function property_exists;

/**
 * @mixin EuromonitorResource
 */
trait SupportsUnifiedCurrency
{
    public ?Currency $unifiedCurrency = null;

    public function unifiedCurrency(Currency|string $unifiedCurrency): static
    {
        $this->unifiedCurrency = $unifiedCurrency instanceof Currency ? $unifiedCurrency : Currency::tryFrom($unifiedCurrency);

        return $this;
    }

    public function inUSD(): static
    {
        $this->unifiedCurrency = Currency::USD;

        return $this;
    }

    public function inEUR(): static
    {
        $this->unifiedCurrency = Currency::EUR;

        return $this;
    }

    public function inGBP(): static
    {
        $this->unifiedCurrency = Currency::GBP;

        return $this;
    }

    public function inJPY(): static
    {
        $this->unifiedCurrency = Currency::JPY;

        return $this;
    }

    public function inCHF(): static
    {
        $this->unifiedCurrency = Currency::CHF;

        return $this;
    }

    public function buildQuerySupportsUnifiedCurrency(array $query): array
    {
        if (is_null($this->unifiedCurrency)) {
            return $query;
        }

        if ($this->forMassExtraction()) {
            $query['UnifiedCurrency'] = $this->unifiedCurrency->code();
        } else {
            $query['unifiedCurrency'] = $this->unifiedCurrency->value;
        }

        return $query;
    }
}
