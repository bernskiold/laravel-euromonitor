<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;

/**
 * @mixin EuromonitorResource
 */
trait SupportsPagination
{
    public int $currentPage = 0;

    public int $perPage = 1_000;

    public function buildQuerySupportsPagination(array $query): array
    {
        if($this->perPage > 10_000) {
            throw new InvalidEuromonitorQueryException('The maximum number of records per page is 10,000.');
        }

        $query['limit'] = $this->perPage;
        $query['offset'] = $this->currentPage * $this->perPage;

        return $query;
    }
}
