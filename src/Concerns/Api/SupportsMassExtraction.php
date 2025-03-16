<?php

namespace Bernskiold\LaravelEuromonitor\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Data\JobResponse;
use Bernskiold\LaravelEuromonitor\Enum\FileType;

trait SupportsMassExtraction
{
    public bool $usesMassExtraction = false;

    public bool $automaticallyDownloadFile = true;

    public FileType $fileType = FileType::Json;

    abstract public function dispatch(): JobResponse;

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

    protected function buildQuerySupportsMassExtraction(array $query): array
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
