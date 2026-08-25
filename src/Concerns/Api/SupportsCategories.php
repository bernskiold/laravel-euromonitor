<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin EuromonitorResource
 */
trait SupportsCategories
{
    public array $categories = [];

    public function categories(Arrayable|array|Model|string|int|null $categories): static
    {
        $this->categories = Arguments::parseSingularOrArrayToArray($categories);

        return $this;
    }

    public function buildQuerySupportsCategories(array $query): array
    {
        $queryKey = $this->usesPascalCaseKeys() ? 'CategoryIds' : 'categoryIds';

        if (! empty($this->categories)) {
            $query[$queryKey] = $this->categories;
        }

        return $query;
    }
}
