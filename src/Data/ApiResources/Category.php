<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

class Category
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $level,
        public readonly string $industryCode,
        public readonly string $industryName,
        public readonly int $industryOrder,
        public readonly ?int $parentId = null,
        public readonly ?string $parentName = null,
    ) {}

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->id,
            name: $response->name,
            level: $response->level,
            industryCode: $response->industryCode,
            industryName: $response->industryName,
            industryOrder: $response->industryOrder,
            parentId: $response->parentId,
            parentName: $response->parentName,
        );
    }
}
