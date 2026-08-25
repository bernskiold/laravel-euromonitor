<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin EuromonitorResource
 */
trait SupportsGeographies
{
    public array $geographies = [];

    public function geographies(Arrayable|array|Model|string|int|null $geographies): static
    {
        $this->geographies = Arguments::parseSingularOrArrayToArray($geographies);

        return $this;
    }

    public function buildQuerySupportsGeographies(array $query): array
    {
        $queryKey = $this->usesPascalCaseKeys() ? 'GeographyIds' : 'geographyIds';

        if (! empty($this->geographies)) {
            $query[$queryKey] = $this->geographies;
        }

        return $query;
    }
}
