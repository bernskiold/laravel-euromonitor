<?php

namespace Bernskiold\LaravelEuromonitor\Enum;

enum FileType: string
{
    case Csv = 'csv';

    case Parquet = 'parquet';

    case Json = 'json';

    public function code(): int
    {
        return match ($this) {
            self::Csv => 0,
            self::Parquet => 1,
            self::Json => 2,
        };
    }
}
