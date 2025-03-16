<?php

namespace Bernskiold\LaravelEuromonitor\Api\Resources;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsContext;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\Category;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;

/**
 * @see https://api-portal.euromonitor.com/user-guide/catalog-service
 */
class Categories extends EuromonitorResource
{
    use SupportsContext;

    /**
     * @throws \Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException
     */
    public function all(): ListResponse
    {
        $response = $this->client->get('/catalog/category', $this->buildQuery());

        return new ListResponse(
            data: $this->mapData($response),
            totalRecords: count($response),
            currentOffset: 0
        );
    }

    protected function mapData(array $data): array
    {
        return array_map(static fn (object $response) => Category::fromResponse($response), $data);
    }
}
