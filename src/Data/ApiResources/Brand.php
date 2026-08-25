<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

/**
 * A brand as returned by the catalog brand endpoint.
 *
 * A brand is returned once per geography and category combination
 * it appears in, so the same brandId will occur multiple times
 * across a full catalog listing. Every brand is owned by a company.
 *
 * @see https://api-portal.euromonitor.com/user-guide/catalog-service
 */
class Brand
{
    public function __construct(
        public readonly int $brandId,
        public readonly string $brandName,
        public readonly ?int $companyId = null,
        public readonly ?string $companyName = null,
        public readonly ?int $geographyId = null,
        public readonly ?string $geographyName = null,
        public readonly ?string $industryCode = null,
        public readonly ?string $industryName = null,
        public readonly ?int $categoryId = null,
        public readonly ?string $categoryName = null,
    ) {}

    public static function fromResponse(object $response): self
    {
        return new self(
            brandId: $response->brandId,
            brandName: $response->brandName,
            companyId: $response->companyId ?? null,
            companyName: $response->companyName ?? null,
            geographyId: $response->geographyId ?? null,
            geographyName: $response->geographyName ?? null,
            industryCode: $response->industryCode ?? null,
            industryName: $response->industryName ?? null,
            categoryId: $response->categoryId ?? null,
            categoryName: $response->categoryName ?? null,
        );
    }
}
