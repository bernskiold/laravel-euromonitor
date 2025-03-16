<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

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
        if (! empty($this->categories)) {
            $query['categoryIds'] = $this->categories;
        }

        return $query;
    }
}
