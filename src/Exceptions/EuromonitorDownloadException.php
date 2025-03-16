<?php

namespace Bernskiold\LaravelEuromonitor\Exceptions;

use Bernskiold\LaravelEuromonitor\Data\JobResponse;
use Exception;

class EuromonitorDownloadException extends Exception
{
    public static function invalidStatus(JobResponse $response): self
    {
        return new static("Could not download Euromonitor file - status not supported: {$response->status}");
    }
}
