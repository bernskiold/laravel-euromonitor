<?php

use Bernskiold\LaravelEuromonitor\Enum\Context;

it('has all expected cases', function () {
    expect(Context::cases())->toHaveCount(2)
        ->and(Context::StatisticsApi->value)->toBe('Statistics')
        ->and(Context::AnalysisApi->value)->toBe('Analysis');
});
