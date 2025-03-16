<?php

namespace Bernskiold\LaravelEuromonitor\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeDownloadedFilesCommand extends Command
{
    protected $signature = 'euromonitor:purge-downloaded-files {--days=1 : The number of days to keep downloaded files.}';

    protected $description = 'Purge all downloaded files from the Euromonitor API.';

    public function handle()
    {
        $this->line('Purging all downloaded files from the Euromonitor API...');

        $disk = config('euromonitor.mass_extraction.disk', 'local');
        $folder = config('euromonitor.mass_extraction.folder', 'euromonitor');

        $files = Storage::disk($disk)->files($folder);
        $fileCount = count($files);

        foreach ($files as $file) {
            if ($this->option('days') && Storage::disk($disk)->lastModified($file) < now()->subDays($this->option('days'))->timestamp) {
                continue;
            }

            Storage::disk($disk)->delete($file);
        }

        $this->info("$fileCount files have been purged.");
    }
}
