<?php

namespace Bernskiold\LaravelEuromonitor\Data\ApiResources;

class Datum
{
    public function __construct(
        public readonly int $year,
        public readonly float $value,
    ) {}

    public static function fromResponse(object $response): self
    {
        return new self(
            year: $response->year,
            value: $response->value,
        );
    }
}
