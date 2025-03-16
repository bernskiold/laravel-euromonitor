<?php

namespace Bernskiold\LaravelEuromonitor\Api\Resources;

use Bernskiold\LaravelEuromonitor\Data\JobDownloadResponse;
use Bernskiold\LaravelEuromonitor\Data\JobResponse;

/**
 * @see https://api-portal.euromonitor.com/user-guide/passport-extractor-api
 */
class Jobs extends EuromonitorResource
{
    public function getDownloadUrl(string $jobId): JobDownloadResponse
    {
        $response = $this->client->get("/statistics/getjobdownloadurl/{$jobId}");

        return new JobDownloadResponse(
            url: $response->jobDownloadUri ?? null,
            message: $response->message ?? null,
            status: $response->status ?? null,
        );
    }

    public function getStatus(string $jobId): JobResponse
    {
        $response = $this->client->get("/statistics/getjobstatus/{$jobId}");

        return new JobResponse(
            jobId: $response->jobId ?? null,
            status: $response->processingStatus ?? null,
            message: $response->message ?? null,
        );
    }

    public function cancel(string $jobId): JobResponse
    {
        $response = $this->client->get("/statistics/cancelJob/{$jobId}");

        return new JobResponse(
            jobId: $jobId,
            status: $response->status ?? null,
            message: $response->message ?? null,
        );
    }
}
