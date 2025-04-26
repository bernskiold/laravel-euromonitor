<?php

namespace Bernskiold\LaravelEuromonitor\Enum;

enum Dataset: string
{

    case BrandShare = 'brand_share';

    case CompanyShare = 'company_share';

    case MarketSize = 'market_size';

    public function label(): string
    {
        return match ($this) {
            self::BrandShare => __('Brand Share'),
            self::CompanyShare => ('Company Share'),
            self::MarketSize => ('Market Size'),
        };
    }

}
