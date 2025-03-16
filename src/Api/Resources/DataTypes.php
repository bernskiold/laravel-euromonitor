<?php

namespace Bernskiold\LaravelEuromonitor\Api\Resources;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsCategories;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsIndustryCodes;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsMeasureType;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\DataType;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Enum\UnitType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;

use function array_map;
use function in_array;

/**
 * @see https://api-portal.euromonitor.com/user-guide/catalog-service
 */
class DataTypes extends EuromonitorResource
{
    use SupportsCategories,
        SupportsIndustryCodes,
        SupportsMeasureType;

    /**
     * @throws InvalidEuromonitorQueryException
     */
    public function all(): ListResponse
    {
        if ($this->measureType === null || ! in_array($this->measureType, UnitType::cases(), true)) {
            throw InvalidEuromonitorQueryException::noMeasureType();
        }

        $endpoint = "/catalog/{$this->measureType->value}/datatype";
        $response = $this->client->get($endpoint, $this->buildQuery());

        return new ListResponse(
            data: $this->mapData($response),
            totalRecords: count($response),
            currentOffset: 0
        );
    }

    protected function mapData(array $data): array
    {
        return array_map(static fn (object $response) => DataType::fromResponse($response), $data);
    }
}
