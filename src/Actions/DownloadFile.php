<?php

namespace Bernskiold\LaravelEuromonitor\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class DownloadFile
{
    public function execute(string $fileUrl, ?callable $callback = null): string
    {
        $disk = config('euromonitor.mass_extraction.disk', 'local');
        $folder = config('euromonitor.mass_extraction.folder', 'euromonitor');

        $filename = basename($fileUrl);
        $path = "{$folder}/{$filename}";

        $response = Http::get($fileUrl);

        if (!$response->successful()) {
            throw new RuntimeException("Failed to download file from {$fileUrl}");
        }

        Storage::disk($disk)->put($path, $response->body());

        if ($callback) {
            $callback($path, $disk);
        }

        return $path;
    }
}
