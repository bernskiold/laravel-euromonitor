<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;

/**
 * Pagination for the catalog company and brand endpoints.
 *
 * Unlike the statistics services, which page with a zero-based
 * offset and a limit, the catalog endpoints page with a one-based
 * page number and a record count.
 *
 * @mixin EuromonitorResource
 */
trait SupportsCatalogPagination
{
    public const MaximumRecordsPerPage = 10_000;

    public int $currentPage = 1;

    public int $perPage = self::MaximumRecordsPerPage;

    public function page(int $page): static
    {
        $this->currentPage = $page;

        return $this;
    }

    public function perPage(int $perPage): static
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * @throws InvalidEuromonitorQueryException
     */
    public function buildQuerySupportsCatalogPagination(array $query): array
    {
        if ($this->perPage > self::MaximumRecordsPerPage) {
            throw new InvalidEuromonitorQueryException('The maximum number of records per page is 10,000.');
        }

        if ($this->currentPage < 1) {
            throw new InvalidEuromonitorQueryException('The page number starts at 1.');
        }

        $query['PageNumber'] = $this->currentPage;
        $query['NoOfRecords'] = $this->perPage;

        return $query;
    }
}
