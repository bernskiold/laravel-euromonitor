<?php

namespace Bernskiold\LaravelEuromonitor\Jobs;

use Bernskiold\LaravelEuromonitor\Actions\DownloadFile;
use Bernskiold\LaravelEuromonitor\Enum\Dataset;
use Bernskiold\LaravelEuromonitor\Events\EuromonitorFileDownloaded;
use Bernskiold\LaravelEuromonitor\Exceptions\EuromonitorDownloadException;
use Bernskiold\LaravelEuromonitor\Facades\Euromonitor;
use DateTime;
use DateTimeInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use Throwable;
use function config;
use function event;

class DownloadEuromonitorFile implements ShouldQueue, ShouldBeUnique
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string  $jobId,
        public Dataset $dataset,
    )
    {
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
            $this->release(config('euromonitor.mass_extraction.wait_time', 60));

            return;
        }

        if ($job->status === 'Processing') {
            Log::debug('Euromonitor job is processing, waiting...', ['jobId' => $this->jobId]);
            $this->release(config('euromonitor.mass_extraction.wait_time', 60));

            return;
        }

        if ($job->status !== 'Processed') {
            Log::error('Could not download Euromonitor file: Invalid job status', ['jobId' => $this->jobId, 'status' => $job->status]);
            $this->fail(EuromonitorDownloadException::invalidStatus($job));

            return;
        }

        $fileDetails = Euromonitor::jobs()->getDownloadUrl($this->jobId);

        $downloadAction->execute($fileDetails->url, function (string $path, string $disk) {
            event(new EuromonitorFileDownloaded($this->dataset, $this->jobId, $path, $disk));
        });
    }

    public function retryUntil(): DateTime
    {
        return now()->addHours(2);
    }

    public function middleware(): array
    {
        return [
            new RateLimited('euromonitor-download'),
        ];
    }

    public function uniqueId(): string
    {
        return $this->jobId;
    }

    public function tags(): array
    {
        return [
            'euromonitor',
            'download',
            'job-id:'.$this->jobId,
            'dataset:'.$this->dataset->value,
        ];
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Failed to download Euromonitor file', [
            'jobId' => $this->jobId,
            'dataset' => $this->dataset->value,
            'exception' => $exception?->getMessage(),
        ]);
    }
}
