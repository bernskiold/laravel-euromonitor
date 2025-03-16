<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

trait SupportsGeographies
{
    public array $geographies = [];

    public function geographies(Arrayable|array|Model|string|int|null $geographies): static
    {
        $this->geographies = Arguments::parseSingularOrArrayToArray($geographies);

        return $this;
    }

    protected function buildQuerySupportsGeographies(array $query): array
    {
        if (! empty($this->geographies)) {
            $query['geographyIds'] = $this->geographies;
        }

        return $query;
    }
}
