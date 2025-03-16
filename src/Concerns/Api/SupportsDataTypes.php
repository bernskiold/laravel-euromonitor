<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

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
        if (! empty($this->dataTypes)) {
            $query['dataTypeIds'] = $this->dataTypes;
        }

        return $query;
    }
}
