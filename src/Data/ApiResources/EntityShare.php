<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

use Illuminate\Support\Collection;

use function collect;
use function is_numeric;

/**
 * A single company share or brand share record.
 *
 * Since the 2025 API update both the company and brand share services
 * return stable Euromonitor identifiers alongside the names, so records
 * can be keyed on IDs rather than matched on name. The identifiers are
 * still typed as nullable because older extract files, and any custom
 * data slice that omits them, will not carry them.
 *
 * @see https://api-portal.euromonitor.com/user-guide/company-and-brand-share
 */
class EntityShare
{
    public readonly Collection $data;

    public function __construct(
        public readonly ?int $researchYear = null,
        public readonly ?int $geographyId = null,
        public readonly ?string $geographyName = null,
        public readonly ?int $categoryId = null,
        public readonly ?string $categoryName = null,
        public readonly ?string $industryCode = null,
        public readonly ?string $industryName = null,
        public readonly ?int $companyId = null,
        public readonly ?string $companyName = null,
        public readonly ?int $brandId = null,
        public readonly ?string $brandName = null,
        public readonly ?int $dataTypeId = null,
        public readonly ?string $dataTypeName = null,
        public readonly ?int $shareTypeId = null,
        public readonly ?string $shareTypeName = null,
        public readonly ?string $exchangeRateName = null,
        public readonly ?string $unit = null,
        public readonly ?int $unitMultiplier = null,
        ?Collection $data = null,
    ) {
        $this->data = $data ?? new Collection;
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            researchYear: $response->researchYear ?? null,
            geographyId: $response->geographyId ?? null,
            geographyName: $response->geographyName ?? null,
            categoryId: $response->categoryId ?? null,
            categoryName: $response->categoryName ?? null,
            industryCode: $response->industryCode ?? null,
            industryName: $response->industryName ?? null,
            companyId: $response->companyId ?? null,
            companyName: $response->companyName ?? null,
            brandId: $response->brandId ?? null,
            brandName: $response->brandName ?? null,
            dataTypeId: $response->dataTypeId ?? null,
            dataTypeName: $response->dataTypeName ?? null,
            shareTypeId: $response->shareTypeId ?? null,
            shareTypeName: $response->shareTypeName ?? null,
            exchangeRateName: $response->exchangeRateName ?? null,
            unit: $response->unit ?? null,
            unitMultiplier: $response->unitMultiplier ?? null,
            data: collect($response->data ?? [])
                ->map(fn (object $datum) => Datum::fromResponse($datum)),
        );
    }

    public static function fromFile(object $row): self
    {
        return new self(
            researchYear: $row->ResearchYear ?? null,
            geographyId: $row->GeographyId ?? null,
            geographyName: $row->GeographyName ?? null,
            categoryId: $row->CategoryId ?? null,
            categoryName: $row->CategoryName ?? null,
            industryCode: $row->IndustryCode ?? null,
            industryName: $row->IndustryName ?? null,
            companyId: $row->CompanyId ?? null,
            companyName: $row->CompanyName ?? null,
            brandId: $row->BrandId ?? null,
            brandName: $row->BrandName ?? null,
            dataTypeId: $row->DataTypeId ?? null,
            dataTypeName: $row->DataTypeName ?? null,
            shareTypeId: $row->ShareTypeId ?? null,
            shareTypeName: $row->ShareTypeName ?? null,
            exchangeRateName: $row->ExchangeRateName ?? null,
            unit: $row->Unit ?? null,
            unitMultiplier: $row->UnitMultiplier ?? null,
            data: collect($row)
                ->filter(fn ($value, $key) => is_numeric($key))
                ->map(fn ($value, $year) => new Datum((int) $year, $value))
        );
    }

    /**
     * The identifier of the entity this record describes.
     *
     * Brand share records describe a brand, company share records
     * describe a company.
     */
    public function entityId(): ?int
    {
        return $this->brandId ?? $this->companyId;
    }

    /**
     * The name of the entity this record describes.
     */
    public function entityName(): ?string
    {
        return $this->brandName ?? $this->companyName;
    }

    /**
     * Whether this record carries the stable Euromonitor identifier
     * for the entity it describes.
     */
    public function hasEntityId(): bool
    {
        return $this->entityId() !== null;
    }
}
