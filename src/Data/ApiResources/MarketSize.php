<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

use Illuminate\Support\Collection;

class MarketSize
{
    public readonly Collection $data;

    public function __construct(
        public readonly int $researchYear,
        public readonly int $geographyId,
        public readonly string $geographyName,
        public readonly int $categoryId,
        public readonly string $categoryName,
        public readonly string $industry,
        public readonly int $dataTypeId,
        public readonly string $dataType,
        public readonly string $unitName,
        public readonly string $inflationType,
        public readonly string $exchangeRateName,
        public readonly string $perCapitaName,
        public readonly int $unitMultiplier,
        ?Collection $data = null,
    ) {
        $this->data = $data ?? new Collection;
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            researchYear: $response->researchYear,
            geographyId: $response->geographyId,
            geographyName: $response->geographyName,
            categoryId: $response->categoryId,
            categoryName: $response->categoryName,
            industry: $response->industry,
            dataTypeId: $response->dataTypeId,
            dataType: $response->dataType,
            unitName: $response->unitName,
            inflationType: $response->inflationType,
            exchangeRateName: $response->exchangeRateName,
            perCapitaName: $response->perCapitaName,
            unitMultiplier: $response->unitMultiplier,
            data: collect($response->data)
                ->map(fn (object $datum) => Datum::fromResponse($datum)),
        );
    }
}
