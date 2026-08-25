<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin EuromonitorResource
 */
trait SupportsBrands
{
    public array $brands = [];

    public function brands(Arrayable|array|Model|string|int|null $brands): static
    {
        $this->brands = Arguments::parseSingularOrArrayToArray($brands);

        return $this;
    }

    public function buildQuerySupportsBrands(array $query): array
    {
        $queryKey = $this->usesPascalCaseKeys() ? 'BrandIds' : 'brandIds';

        if (! empty($this->brands)) {
            $query[$queryKey] = $this->brands;
        }

        return $query;
    }
}
