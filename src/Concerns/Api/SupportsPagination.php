<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

trait SupportsPagination
{
    public int $currentPage = 0;

    public int $perPage = 1_000;

    public function buildQuerySupportsPagination(array $query): array
    {
        $query['limit'] = $this->perPage;
        $query['offset'] = $this->currentPage * $this->perPage;

        return $query;
    }
}
