<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

class DataSlice
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public string $industryCode,
        public string $industryName,
    ) {}

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->id,
            name: $response->name,
            industryCode: $response->industryCode,
            industryName: $response->industryName
        );
    }
}
