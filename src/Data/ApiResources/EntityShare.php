<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

use Illuminate\Support\Collection;
use function collect;
use function is_numeric;

class EntityShare
{
    public readonly Collection $data;

    public function __construct(
        public readonly int $researchYear,
        public readonly int $geographyId,
        public readonly string $geographyName,
        public readonly int $categoryId,
        public readonly string $categoryName,
        public readonly string $industryCode,
        public readonly string $industryName,
        public readonly string $entityName,
        public readonly int $dataTypeId,
        public readonly string $dataTypeName,
        public readonly int $shareTypeId,
        public readonly ?string $shareTypeName,
        public readonly ?string $exchangeRateName,
        public readonly string $unit,
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
            industryCode: $response->industryCode,
            industryName: $response->industryName,
            entityName: $response->companyName ?? $response->brandName,
            dataTypeId: $response->dataTypeId,
            dataTypeName: $response->dataTypeName,
            shareTypeId: $response->shareTypeId,
            shareTypeName: $response->shareTypeName,
            exchangeRateName: $response->exchangeRateName,
            unit: $response->unit,
            unitMultiplier: $response->unitMultiplier,
            data: collect($response->data ?? [])
                ->map(fn (object $datum) => Datum::fromResponse($datum)),
        );
    }

    public static function fromFile(object $row): self
    {
        return new self(
            researchYear: $row->ResearchYear,
            geographyId: $row->GeographyId,
            geographyName: $row->GeographyName,
            categoryId: $row->CategoryId,
            categoryName: $row->CategoryName,
            industryCode: $row->IndustryCode,
            industryName: $row->IndustryName,
            entityName: $row->CompanyName ?? $row->BrandName,
            dataTypeId: $row->DataTypeId,
            dataTypeName: $row->DataTypeName,
            shareTypeId: $row->ShareTypeId,
            shareTypeName: $row->ShareTypeName,
            exchangeRateName: $row->ExchangeRateName,
            unit: $row->Unit,
            unitMultiplier: $row->UnitMultiplier,
            data: collect($row)
                ->filter(fn ($value, $key) => is_numeric($key))
                ->map(fn ($value, $year) => new Datum($year, $value))
        );
    }
}
