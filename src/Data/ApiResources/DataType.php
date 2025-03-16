<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

class DataType
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->id,
            name: $response->name,
        );
    }
}
