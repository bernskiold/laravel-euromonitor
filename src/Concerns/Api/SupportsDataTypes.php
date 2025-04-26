<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin EuromonitorResource
 */
trait SupportsDataTypes
{
    public array $dataTypes = [];

    public function dataTypes(Arrayable|array|Model|string|int|null $dataTypes): static
    {
        $this->dataTypes = Arguments::parseSingularOrArrayToArray($dataTypes);

        return $this;
    }

    public function buildQuerySupportsDataTypes(array $query): array
    {
        $queryKey = $this->forMassExtraction() ? 'DataTypeIds' : 'dataTypeIds';

        if (! empty($this->dataTypes)) {
            $query[$queryKey] = $this->dataTypes;
        }

        return $query;
    }
}
