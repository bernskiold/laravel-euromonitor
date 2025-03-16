<?php

namespace Bernskiold\LaravelEuromonitor\Facades;

use Bernskiold\LaravelEuromonitor\Api\Resources;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Resources\BrandShare brandShares()
 * @method static Resources\Categories categories()
 * @method static Resources\CompanyShare companyShares()
 * @method static Resources\DataTypes dataTypes()
 * @method static Resources\Geographies geographies()
 * @method static Resources\Jobs jobs()
 * @method static Resources\MarketSize marketSize()
 *
 * @see \Bernskiold\LaravelEuromonitor\Api\Euromonitor
 */
class Euromonitor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-euromonitor';
    }
}
