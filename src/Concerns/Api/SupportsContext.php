<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Enum\Context;

use function is_null;

/**
 * @mixin EuromonitorResource
 */
trait SupportsContext
{
    public ?Context $context = null;

    public function context(Context|string $context): static
    {
        $this->context = $context instanceof Context ? $context : Context::tryFrom($context);

        return $this;
    }

    public function forStatisticsApi(): static
    {
        $this->context = Context::StatisticsApi;

        return $this;
    }

    public function forAnalysisApi(): static
    {
        $this->context = Context::AnalysisApi;

        return $this;
    }

    public function buildQuerySupportsContext(array $query): array
    {
        if (! is_null($this->context)) {
            $query['context'] = $this->context->value;
        }

        return $query;
    }
}
