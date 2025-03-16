<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

class Geography
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $isoAlpha2Code = null,
        public readonly ?int $parentId = null,
        public readonly ?string $parentName = null,
    ) {}

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->id,
            name: $response->name,
            isoAlpha2Code: $response->isoAlpha2Code,
            parentId: $response->parentId,
            parentName: $response->parentName,
        );
    }
}
