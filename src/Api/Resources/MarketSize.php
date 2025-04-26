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
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsPagination;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsPerCapita;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsUnifiedCurrency;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\MarketSize as MarketSizeResource;
use Bernskiold\LaravelEuromonitor\Data\JobResponse;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Enum\Dataset;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Bernskiold\LaravelEuromonitor\Jobs\DownloadEuromonitorFile;

use function array_map;
use function config;
use function dispatch;
use function now;

/**
 * @see https://api-portal.euromonitor.com/user-guide/market-size
 */
class MarketSize extends EuromonitorResource
{
    use SupportsCategories,
        SupportsDataSlice,
        SupportsDataTypes,
        SupportsExchangeRate,
        SupportsGeographies,
        SupportsGrowthType,
        SupportsIndustryCodes,
        SupportsInflationType,
        SupportsLocalCurrency,
        SupportsMassExtraction,
        SupportsPagination,
        SupportsPerCapita,
        SupportsUnifiedCurrency;

    /**
     * @throws InvalidEuromonitorQueryException
     */
    public function all(): ListResponse
    {
        $response = $this->client->post('/statistics/marketsizes', $this->buildQuery());

        return new ListResponse(
            data: $this->mapData($response->marketSizes ?? []),
            totalRecords: $response->total ?? 0,
            currentOffset: $response->offset ?? 0,
        );
    }

    public function dispatch(): JobResponse
    {
        $this->queueable();

        $response = $this->client->post('/statistics/createmarketsizejob', $this->buildQuery());

        if ($this->automaticallyDownloadFile && isset($response->jobId)) {
            dispatch(new DownloadEuromonitorFile($response->jobId,Dataset::MarketSize))
                ->delay(now()->addMinutes(5));
        }

        return new JobResponse(
            jobId: $response->jobId ?? null,
            status: $response->processingStatus ?? null,
            message: $response->message ?? null,
        );
    }

    protected function mapData(array $data): array
    {
        return array_map(static fn (object $response) => MarketSizeResource::fromResponse($response), $data);
    }

    protected function getRules(): array
    {
        if($this->forMassExtraction()) {
            return [
                'CategoryIds' => ['array', 'required_without:industryCodes'],
                'IndustryCode' => ['string', 'required'],
            ];
        }

        return [
            'categoryIds' => ['array', 'required_without:industryCodes'],
            'industryCodes' => ['array', 'required_without:categoryIds'],
        ];
    }
}
