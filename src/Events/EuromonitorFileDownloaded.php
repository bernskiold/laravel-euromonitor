<?php

namespace Bernskiold\LaravelEuromonitor\Events;

use Bernskiold\LaravelEuromonitor\Enum\Dataset;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EuromonitorFileDownloaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Dataset $dataset,
        public string $jobId,
        public string $filePath,
        public string $disk,
    ) {
    }
}
