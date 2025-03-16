<?php

namespace Bernskiold\LaravelEuromonitor\Data;

use Illuminate\Support\Collection;

use function is_array;

class ListResponse
{
    protected Collection $data;

    public function __construct(
        null|array|Collection $data = [],
        public readonly int $totalRecords = 0,
        public readonly int $currentOffset = 0,
    ) {
        $this->data = match (true) {
            $data instanceof Collection => $data,
            is_array($data) => new Collection($data),
            default => new Collection,
        };
    }

    public function get(): Collection
    {
        return $this->data;
    }
}
