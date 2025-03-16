<?php

use Bernskiold\LaravelEuromonitor\Enum\FileType;

it('has all expected cases', function () {
    expect(FileType::cases())->toHaveCount(3)
        ->and(FileType::Csv->value)->toBe('csv')
        ->and(FileType::Parquet->value)->toBe('parquet')
        ->and(FileType::Json->value)->toBe('json');
});

it('maps cases to correct codes', function () {
    expect(FileType::Csv->code())->toBe(0)
        ->and(FileType::Parquet->code())->toBe(1)
        ->and(FileType::Json->code())->toBe(2);
});
