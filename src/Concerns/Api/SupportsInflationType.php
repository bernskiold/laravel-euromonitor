<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Enum\InflationType;

use function property_exists;

trait SupportsInflationType
{
    public ?InflationType $inflationType = null;

    public function inflationType(InflationType|string $inflationType): static
    {
        $this->inflationType = $inflationType instanceof InflationType ? $inflationType : InflationType::tryFrom($inflationType);

        return $this;
    }

    public function buildQuerySupportsInflationType(array $query): array
    {
        if (is_null($this->inflationType)) {
            return $query;
        }

        if (property_exists($this, 'usesMassExtraction') && $this->usesMassExtraction) {
            $query['InflationType'] = $this->inflationType->code();
        } else {
            $query['inflationType'] = $this->inflationType->value;
        }

        return $query;
    }
}
