<?php

namespace Bernskiold\LaravelEuromonitor\Data;

class JobResponse
{
    public function __construct(
        public readonly ?string $jobId,
        public readonly ?string $status,
        public readonly ?string $message,
    ) {}
}
