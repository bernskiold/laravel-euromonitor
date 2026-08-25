<?php

namespace Bernskiold\LaravelEuromonitor\Tests\Doubles;

use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Api\Resources\EuromonitorResource;

/**
 * A resource stand-in for exercising the query-building concerns
 * in isolation.
 *
 * The concerns are written against EuromonitorResource, so they need a
 * real resource to resolve forMassExtraction() and usesPascalCaseKeys()
 * against. Tests toggle $usesMassExtraction directly to assert the
 * PascalCase and enum-code branches.
 */
class FakeResource extends EuromonitorResource
{
    public bool $usesMassExtraction = false;

    public function __construct(?EuromonitorApiClient $client = null)
    {
        parent::__construct($client ?? new EuromonitorApiClient(
            username: 'test-username',
            password: 'test-password',
            apiKey: 'test-api-key',
            baseUrl: 'https://api.euromonitor.com',
            version: '1.0',
        ));
    }

    /**
     * Expose the protected helper so tests can assert on it directly.
     */
    public function pascalCaseKeys(): bool
    {
        return $this->usesPascalCaseKeys();
    }
}
