<?php

namespace Bernskiold\LaravelEuromonitor\Actions;

use Bernskiold\LaravelEuromonitor\Data\ApiResources\EntityShare;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ParseEntityShareFile
{

    public function execute(string $disk, string $filePath): Collection
    {
        $contents = Storage::disk($disk)->get($filePath);
        $parsedData = json_decode($contents, false);

        return collect($parsedData)
            ->map(fn (object $row) => EntityShare::fromFile($row));
    }

}
