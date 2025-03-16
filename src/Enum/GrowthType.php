<?php

namespace Bernskiold\LaravelEuromonitor\Enum;

enum GrowthType: string
{
    case None = 'None';

    case YearOnYear = 'YearOnYear';

    public function code(): int
    {
        return match ($this) {
            self::None => 0,
            self::YearOnYear => 1,
        };
    }
}
