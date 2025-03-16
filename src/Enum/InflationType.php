<?php

namespace Bernskiold\LaravelEuromonitor\Enum;

enum InflationType: string
{
    case Current = 'Current';

    case Constant = 'Constant';

    case Both = 'Both';

    public function code(): int
    {
        return match ($this) {
            self::Both => 0,
            self::Current => 3,
            self::Constant => 5,
        };
    }
}
