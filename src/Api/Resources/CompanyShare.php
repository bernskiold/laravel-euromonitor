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
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsShareType;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsUnifiedCurrency;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsUnit;
use Bernskiold\LaravelEuromonitor\Data\ApiResources\EntityShare;
use Bernskiold\LaravelEuromonitor\Data\JobResponse;
use Bernskiold\LaravelEuromonitor\Data\ListResponse;
use Bernskiold\LaravelEuromonitor\Enum\Dataset;
use Bernskiold\LaravelEuromonitor\Enum\UnitType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Bernskiold\LaravelEuromonitor\Jobs\DownloadEuromonitorFile;

use function array_map;
use function config;
use function dispatch;
use function in_array;
use function now;

/**
 * @see https://api-portal.euromonitor.com/user-guide/company-and-brand-share
 */
class CompanyShare extends EuromonitorResource
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
        SupportsShareType,
        SupportsUnifiedCurrency,
        SupportsUnit;

    /**
     * @throws InvalidEuromonitorQueryException
     */
    public function all(): ListResponse
    {
        if ($this->unit === null) {
            throw InvalidEuromonitorQueryException::noUnit();
        }

        $endpoint = "/statistics/companyshare/{$this->unit->value}";
        $response = $this->client->post($endpoint, $this->buildQuery());

        return new ListResponse(
            data: $this->mapData($response->companyShares ?? []),
            totalRecords: $response->total ?? 0,
            currentOffset: $response->offset ?? 0,
        );
    }

    public function dispatch(): JobResponse
    {
        if ($this->unit === null) {
            throw InvalidEuromonitorQueryException::noUnit();
        }

        $this->queueable();

        $query = $this->buildQuery();
        $query['UnitType'] = $this->unit->code();

        $response = $this->client->post('/statistics/createcompanysharejob', $query);

        if ($this->automaticallyDownloadFile && isset($response->jobId)) {
            dispatch(new DownloadEuromonitorFile($response->jobId, Dataset::CompanyShare))
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
        return array_map(static fn (object $response) => EntityShare::fromResponse($response), $data);
    }

    protected function getRules(): array
    {
        if($this->forMassExtraction()) {
            return [
                'ShareTypeId' => ['required'],
                'GeographyIds' => ['array', 'required'],
                'CategoryIds' => ['array', 'nullable'],
                'IndustryCode' => ['required', 'string'],
            ];
        }

        return [
            'shareTypeId' => ['required'],
            'geographyIds' => ['array', 'required'],
            'categoryIds' => ['array', 'required_without:industryCodes'],
            'industryCodes' => ['array', 'required_without:categoryIds'],
        ];
    }
}
