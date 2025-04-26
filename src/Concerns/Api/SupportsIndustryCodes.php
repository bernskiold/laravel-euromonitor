<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin EuromonitorResource
 */
trait SupportsIndustryCodes
{
    public array $industryCodes = [];

    public function industryCodes(Arrayable|array|Model|string|int|null $industryCodes): static
    {
        $this->industryCodes = Arguments::parseSingularOrArrayToArray($industryCodes);

        return $this;
    }

    protected function buildQuerySupportsIndustryCodes(array $query): array
    {
        if($this->forMassExtraction()) {
            if(count($this->industryCodes) > 1) {
                throw new InvalidEuromonitorQueryException('You can only use one industry code for mass extraction.');
            }

           $query['IndustryCode'] = Arr::first($this->industryCodes);
        } else {
            $query['industryCodes'] = $this->industryCodes;
        }

        return $query;
    }
}
