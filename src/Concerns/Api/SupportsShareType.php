<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

trait SupportsShareType
{
    public int|string $shareType = 0;

    public function shareType(int|string $shareType): static
    {
        $this->shareType = $shareType;

        return $this;
    }
}
