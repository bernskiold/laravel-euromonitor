<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

trait SupportsPagination
{
    public int $currentPage = 0;

    public ?int $perPage = null;

    protected function addPaginationToQuery(array $query): array
    {
        $query['limit'] = $this->perPage;
        $query['offset'] = ($this->currentPage + 1) * $this->perPage;

        return $query;
    }
}
