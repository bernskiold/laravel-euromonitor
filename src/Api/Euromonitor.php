<?php

namespace Bernskiold\LaravelEuromonitor\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\BrandShare;
use Bernskiold\LaravelEuromonitor\Api\Resources\Categories;
use Bernskiold\LaravelEuromonitor\Api\Resources\CompanyShare;
use Bernskiold\LaravelEuromonitor\Api\Resources\DataTypes;
use Bernskiold\LaravelEuromonitor\Api\Resources\Geographies;
use Bernskiold\LaravelEuromonitor\Api\Resources\Jobs;
use Bernskiold\LaravelEuromonitor\Api\Resources\MarketSize;

/**
 * Euromonitor API
 *
 * This class is the main entry point for the Euromonitor API.
 * It provides access to all the different resources available.
 *
 * You can either call this class directly via the container or
 * use the Euromonitor facade provided by the package.
 */
class Euromonitor
{
    public function __construct(
        protected EuromonitorApiClient $apiClient,
    ) {}

    public function brandShares(): BrandShare
    {
        return new BrandShare($this->apiClient);
    }

    public function categories(): Categories
    {
        return new Categories($this->apiClient);
    }

    public function companyShares(): CompanyShare
    {
        return new CompanyShare($this->apiClient);
    }

    public function dataTypes(): DataTypes
    {
        return new DataTypes($this->apiClient);
    }

    public function geographies(): Geographies
    {
        return new Geographies($this->apiClient);
    }

    public function jobs(): Jobs
    {
        return new Jobs($this->apiClient);
    }

    public function marketSize(): MarketSize
    {
        return new MarketSize($this->apiClient);
    }
}
