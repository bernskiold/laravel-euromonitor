<?php

namespace Bernskiold\LaravelEuromonitor\Jobs;

use Bernskiold\LaravelEuromonitor\Actions\DownloadFile;
use Bernskiold\LaravelEuromonitor\Enum\Dataset;
use Bernskiold\LaravelEuromonitor\Events\EuromonitorFileDownloaded;
use Bernskiold\LaravelEuromonitor\Exceptions\EuromonitorDownloadException;
use Bernskiold\LaravelEuromonitor\Facades\Euromonitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use function event;

class DownloadEuromonitorFile implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $jobId,
        public Dataset $dataset,
    ) {
        $this->onQueue(config('euromonitor.mass_extraction.queue', 'default'));
    }

    public function handle(DownloadFile $downloadAction): void
    {
        $job = Euromonitor::jobs()->getStatus($this->jobId);

        if ($job->status === 'NoData') {
            Log::info('Could not download Euromonitor file: No data for query', ['jobId' => $this->jobId]);

            return;
        }

        if ($job->status === 'Canceled') {
            Log::debug('Could not download Euromonitor file: Job was cancelled', ['jobId' => $this->jobId]);

            return;
        }

        if ($job->status === 'Queued') {
            Log::debug('Euromonitor job is still queued, waiting...', ['jobId' => $this->jobId]);
            $this->release(config('euromonitor.mass_extraction.wait_time', 15));

            return;
        }

        if ($job->status !== 'Processed') {
            Log::error('Could not download Euromonitor file: Invalid job status', ['jobId' => $this->jobId, 'status' => $job->status]);
            $this->fail(EuromonitorDownloadException::invalidStatus($job));

            return;
        }

        $fileDetails = Euromonitor::jobs()->getDownloadUrl($this->jobId);

        $downloadAction->execute($fileDetails->url);

        event(new EuromonitorFileDownloaded($this->dataset, $this->jobId, $fileDetails->url));
    }
}
