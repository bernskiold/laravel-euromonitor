<?php

namespace Bernskiold\LaravelEuromonitor\Enum;

enum ExchangeRate: string
{
    case Fixed = 'Fixed';

    case YearOnYear = 'YearOnYear';

    case Both = 'Both';

    public function code(): int
    {
        return match ($this) {
            self::Both => 0,
            self::Fixed => 1,
            self::YearOnYear => 2,
        };
    }
}
