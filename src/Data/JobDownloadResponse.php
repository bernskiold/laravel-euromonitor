<?php

namespace Bernskiold\LaravelEuromonitor\Data;

class JobDownloadResponse
{
    public function __construct(
        public readonly ?string $url,
        public readonly ?string $message,
        public readonly ?string $status,
    ) {}
}
