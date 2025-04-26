<?php

namespace Bernskiold\LaravelEuromonitor\Api\Resources;

use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsCategories;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsDataSlice;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsDataTypes;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsExchangeRate;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsGeographies;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsGrowthType;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsIndustryCodes;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsInflationType;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsLocalCurrency;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsMassExtraction;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsMeasureType;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsPagination;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsShareType;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsUnifiedCurrency;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsUnit;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\DataType;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\EntityShare;
use Bernskiold\LaravelEuromonitor\Data\JobResponse;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Enum\Dataset;
use Bernskiold\LaravelEuromonitor\Enum\UnitType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Bernskiold\LaravelEuromonitor\Jobs\DownloadEuromonitorFile;

use function array_map;
use function count;
use function dispatch;

/**
 * @see https://api-portal.euromonitor.com/user-guide/company-and-brand-share
 */
class ShareType extends EuromonitorResource
{
    use SupportsMeasureType,
        SupportsIndustryCodes,
        SupportsCategories;

    /**
     * @throws \Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException
     */
    public function all(): ListResponse
    {
        if ($this->measureType === null) {
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
        return array_map(static fn (object $response) => \Bernskiold\LaravelEuromonitor\Data\ApiResources\ShareType::fromResponse($response), $data);
    }
}
