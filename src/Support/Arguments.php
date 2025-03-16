<?php

namespace Bernskiold\LaravelEuromonitor\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

use function is_int;
use function is_string;

class Arguments
{
    /**
     * Parses a singular value or an array-like value to an array.
     */
    public static function parseSingularOrArrayToArray(Arrayable|array|int|null|string|Model $value): array
    {
        if ($value === null) {
            return [];
        }

        if ($value instanceof Model) {
            return [$value->getKey()];
        }

        if (is_string($value)) {
            return explode(',', $value);
        }

        if (is_int($value)) {
            return [$value];
        }

        if ($value instanceof Arrayable) {
            return $value->toArray();
        }

        return $value;
    }
}
