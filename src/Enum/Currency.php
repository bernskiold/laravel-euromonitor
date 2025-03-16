<?php

namespace Bernskiold\LaravelEuromonitor\Enum;

enum Currency: string
{
    case None = 'None';

    case USD = 'USD';

    case EUR = 'EUR';

    case GBP = 'GBP';

    case JPY = 'JPY';

    case CHF = 'CHF';

    public function code(): int
    {
        return match ($this) {
            self::None => 0,
            self::USD => 1,
            self::EUR => 2,
            self::GBP => 3,
            self::JPY => 4,
            self::CHF => 5,
        };
    }
}
