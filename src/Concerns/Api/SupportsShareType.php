<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;

/**
 * @mixin EuromonitorResource
 */
trait SupportsShareType
{
    public int|string $shareType = 0;

    public function shareType(int|string $shareType): static
    {
        $this->shareType = $shareType;

        return $this;
    }

    public function buildQuerySupportsShareType(array $query): array
    {
        $queryKey = $this->usesPascalCaseKeys() ? 'ShareTypeId' : 'shareTypeId';

        $query[$queryKey] = $this->shareType;

        return $query;
    }
}
