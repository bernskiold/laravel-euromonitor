<?php

namespace Bernskiold\LaravelEuromonitor\Enum;

enum UnitType: string
{
    case Percentage = 'Percentage';

    case Actual = 'Actual';

    case Ranking = 'Ranking';

    public function code(): int
    {
        return match ($this) {
            self::Percentage => 0,
            self::Actual => 2,
            self::Ranking => 1,
        };
    }
}
