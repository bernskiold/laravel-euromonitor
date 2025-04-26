<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Enum\MeasureType;

/**
 * @mixin EuromonitorResource
 */
trait SupportsMeasureType
{
    public ?MeasureType $measureType = null;

    public function measureType(MeasureType|string $measureType): static
    {
        $this->measureType = $measureType instanceof MeasureType ? $measureType : MeasureType::tryFrom($measureType);

        return $this;
    }

    public function brandShare(): static
    {
        return $this->measureType(MeasureType::BrandShare);
    }

    public function companyShare(): static
    {
        return $this->measureType(MeasureType::CompanyShare);
    }

    public function marketSize(): static
    {
        return $this->measureType(MeasureType::MarketSize);
    }
}
