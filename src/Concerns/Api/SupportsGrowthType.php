<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Enum\GrowthType;

use function is_null;
use function property_exists;

trait SupportsGrowthType
{
    public ?GrowthType $growthType = null;

    public function growthType(GrowthType $growthType): static
    {
        $this->growthType = $growthType;

        return $this;
    }

    public function yearOnYearGrowth(): static
    {
        return $this->growthType(GrowthType::YearOnYear);
    }

    public function buildQuerySupportsGrowthType(array $query): array
    {
        if (is_null($this->growthType)) {
            return $query;
        }

        if (property_exists($this, 'usesMassExtraction') && $this->usesMassExtraction) {
            $query['GrowthType'] = $this->growthType->code();
        } else {
            $query['growthType'] = $this->growthType->value;
        }

        return $query;
    }
}
