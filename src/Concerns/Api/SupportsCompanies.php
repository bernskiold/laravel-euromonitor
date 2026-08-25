<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin EuromonitorResource
 */
trait SupportsCompanies
{
    public array $companies = [];

    public function companies(Arrayable|array|Model|string|int|null $companies): static
    {
        $this->companies = Arguments::parseSingularOrArrayToArray($companies);

        return $this;
    }

    public function buildQuerySupportsCompanies(array $query): array
    {
        $queryKey = $this->usesPascalCaseKeys() ? 'CompanyIds' : 'companyIds';

        if (! empty($this->companies)) {
            $query[$queryKey] = $this->companies;
        }

        return $query;
    }
}
