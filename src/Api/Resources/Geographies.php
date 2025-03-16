<?php

namespace Bernskiold\LaravelEuromonitor\Api\Resources;

use Bernskiold\LaravelEuromonitor\Data\ApiResources\Geography;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;

use function array_map;

/**
 * @see https://api-portal.euromonitor.com/user-guide/catalog-service
 */
class Geographies extends EuromonitorResource
{
    public function all(): ListResponse
    {
        $response = $this->client->get('/catalog/geography');

        return new ListResponse(
            data: $this->mapData($response),
            totalRecords: count($response),
            currentOffset: 0
        );
    }

    protected function mapData(array $data): array
    {
        return array_map(static fn (object $response) => Geography::fromResponse($response), $data);
    }
}
