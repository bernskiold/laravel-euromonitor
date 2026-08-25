<?php

use Bernskiold\LaravelEuromonitor\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * Load a sample API payload captured from the Euromonitor API portal
 * documentation, decoded the same way the API client decodes a response.
 *
 * @see https://api-portal.euromonitor.com/user-guide/overview
 */
function sample(string $name): object|array
{
    $path = __DIR__.'/Fixtures/'.$name.'.json';

    if (! file_exists($path)) {
        throw new InvalidArgumentException("Sample [{$name}] does not exist at [{$path}].");
    }

    return json_decode(file_get_contents($path), false, 512, JSON_THROW_ON_ERROR);
}

/**
 * The raw JSON string of a fixture, for tests that need file contents.
 */
function sampleContents(string $name): string
{
    $path = __DIR__.'/Fixtures/'.$name.'.json';

    if (! file_exists($path)) {
        throw new InvalidArgumentException("Sample [{$name}] does not exist at [{$path}].");
    }

    return file_get_contents($path);
}
