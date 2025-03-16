<?php

namespace Bernskiold\LaravelEuromonitor\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EuromonitorFileDownloaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $disk;

    public function __construct(
        public string $jobId,
        public string $filePath
    ) {
        $this->disk = config('euromonitor.mass_extraction.disk', 'local');
    }
}
