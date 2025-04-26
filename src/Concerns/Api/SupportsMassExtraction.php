<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;
use Bernskiold\LaravelEuromonitor\Enum\FileType;

/**
 * @mixin EuromonitorResource
 */
trait SupportsMassExtraction
{
    public bool $usesMassExtraction = false;

    public bool $automaticallyDownloadFile = true;

    public FileType $fileType = FileType::Json;

    public function queueable(): self
    {
        $this->usesMassExtraction = true;

        return $this;
    }

    public function downloadFileAutomatically(bool $download = true): self
    {
        $this->automaticallyDownloadFile = $download;

        return $this;
    }

    public function downloadFileManually(): self
    {
        $this->automaticallyDownloadFile = false;

        return $this;
    }

    public function buildQuerySupportsMassExtraction(array $query): array
    {
        if ($this->usesMassExtraction) {
            $query['FileType'] = $this->fileType->code();
        }

        return $query;
    }

    public function asJson(): self
    {
        $this->fileType = FileType::Json;

        return $this;
    }

    public function asCsv(): self
    {
        $this->fileType = FileType::Csv;

        return $this;
    }

    public function asParquet(): self
    {
        $this->fileType = FileType::Parquet;

        return $this;
    }
}
