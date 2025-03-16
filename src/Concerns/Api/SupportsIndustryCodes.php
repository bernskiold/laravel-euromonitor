<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

trait SupportsIndustryCodes
{
    public array $industryCodes = [];

    public function industryCodes(Arrayable|array|Model|string|int|null $industryCodes): static
    {
        $this->industryCodes = Arguments::parseSingularOrArrayToArray($industryCodes);

        return $this;
    }

    protected function buildQueryIndustryCodes(array $query): array
    {
        if (! empty($this->industryCodes)) {
            $query['industryCodes'] = $this->industryCodes;
        }

        return $query;
    }
}
