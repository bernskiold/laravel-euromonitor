<?php

namespace Bernskiold\LaravelEuromonitor\Actions;

use Illuminate\Support\Facades\Storage;

class DownloadFile
{
    public function execute(string $fileUrl, ?callable $callback = null): string
    {
        $disk = config('euromonitor.mass_extraction.disk', 'local');
        $folder = config('euromonitor.mass_extraction.folder', 'euromonitor');

        $filename = basename($fileUrl);
        $path = "{$folder}/{$filename}";

        Storage::disk($disk)->put($path, file_get_contents($fileUrl));

        if ($callback) {
            $callback($path, $disk);
        }

        return $path;
    }
}
