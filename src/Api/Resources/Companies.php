<?php

namespace Bernskiold\LaravelEuromonitor\Api\Resources;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsCatalogPagination;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsCategories;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsGeographies;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\Company;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;

use function array_map;
use function count;

/**
 * The catalog company endpoint.
 *
 * Returns every company your subscription has access to, together
 * with the stable Euromonitor company ID. Use those IDs to filter
 * the company and brand share services instead of matching on name.
 *
 * Note on the path: the API portal user guide prose documents this as
 * "/passport-catalog/company", while the OpenAPI definition published for
 * the same "2. Catalog" API documents "/catalog/company", consistent with
 * every other catalog endpoint. We follow the OpenAPI definition; override
 * the Endpoint constant if a live call ever 404s.
 *
 * @see https://api-portal.euromonitor.com/user-guide/catalog-service
 */
class Companies extends EuromonitorResource
{
    use SupportsCatalogPagination,
        SupportsCategories,
        SupportsGeographies;

    public const Endpoint = '/catalog/company';

    /**
     * @throws InvalidEuromonitorQueryException
     */
    public function all(): ListResponse
    {
        $response = $this->client->get(static::Endpoint, $this->buildQuery());

        return new ListResponse(
            data: $this->mapData((array) $response),
            totalRecords: count((array) $response),
            currentOffset: ($this->currentPage - 1) * $this->perPage,
        );
    }

    protected function mapData(array $data): array
    {
        return array_map(static fn (object $response) => Company::fromResponse($response), $data);
    }

    /**
     * The catalog company and brand endpoints expect PascalCase query keys.
     */
    protected function usesPascalCaseKeys(): bool
    {
        return true;
    }
}
