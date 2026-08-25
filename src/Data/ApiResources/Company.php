<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

/**
 * A company as returned by the catalog company endpoint.
 *
 * A company is returned once per geography and category combination
 * it appears in, so the same companyId will occur multiple times
 * across a full catalog listing.
 *
 * @see https://api-portal.euromonitor.com/user-guide/catalog-service
 */
class Company
{
    public function __construct(
        public readonly int $companyId,
        public readonly string $companyName,
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
            companyId: $response->companyId,
            companyName: $response->companyName,
            geographyId: $response->geographyId ?? null,
            geographyName: $response->geographyName ?? null,
            industryCode: $response->industryCode ?? null,
            industryName: $response->industryName ?? null,
            categoryId: $response->categoryId ?? null,
            categoryName: $response->categoryName ?? null,
        );
    }
}
