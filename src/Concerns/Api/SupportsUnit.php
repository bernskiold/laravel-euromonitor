<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Enum\UnitType;

trait SupportsUnit
{
    public ?UnitType $unit = null;

    public function unit(UnitType|string $unit): static
    {
        $this->unit = $unit instanceof UnitType ? $unit : UnitType::tryFrom($unit);

        return $this;
    }

    public function percentages(): static
    {
        return $this->unit(UnitType::Percentage);
    }

    public function actual(): static
    {
        return $this->unit(UnitType::Actual);
    }

    public function ranking(): static
    {
        return $this->unit(UnitType::Ranking);
    }
}
