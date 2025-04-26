<?php

namespace Bernskiold\LaravelEuromonitor\Api;

use Bernskiold\LaravelEuromonitor\Exceptions\EuromonitorApiException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Throwable;

use function cache;
use function config;
use function http_build_query;
use function now;

/**
 * Euromonitor API Client
 *
 * This class is responsible for making requests to the Euromonitor API.
 * It handles authorization and caching of the access token.
 *
 * @see https://api-portal.euromonitor.com/
 */
class EuromonitorApiClient
{
    protected ?string $accessToken = null;

    public PendingRequest $request;

    public function __construct(
        protected string $username,
        protected string $password,
        protected string $apiKey,
        protected string $baseUrl,
        protected string $version,
    )
    {
        $this->request = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->apiKey,
        ])
            ->contentType('application/json')
            ->accept('application/json; ' . $this->getVersionForHeader());
    }

    protected function getVersionForHeader(): string
    {
        return 'api-version=' . $this->version;
    }

    /**
     * Instantiate the API client from a configuration array.
     */
    public static function fromConfig(array $config): static
    {
        return new static(
            username: $config['username'],
            password: $config['password'],
            apiKey: $config['api_key'],
            baseUrl: $config['base_url'],
            version: $config['version'],
        );
    }

    /**
     * Authorize the API client and get an access token.
     *
     * This requires the use of the Euromonitor API Portal
     * username and password, as well as the account-specific
     * primary API key from the profile details.
     *
     * @see https://api-portal.euromonitor.com/user-guide/authorization-service
     *
     * @throws EuromonitorApiException
     */
    public function getToken(): object
    {
        $response = Http::asForm()
            ->withHeaders([
                'Accept' => 'application/json; ' . $this->getVersionForHeader(),
                'Ocp-Apim-Subscription-Key' => $this->apiKey,
            ])
            ->post($this->baseUrl . '/authentication/connect/token', [
                'username' => $this->username,
                'password' => $this->password,
                'grant_type' => 'password',
            ]);

        if (!$response->successful()) {
            throw EuromonitorApiException::noAccessToken(new \Exception($response->body()));
        }

        return $response->object();
    }

    /**
     * Authorize the API client.
     *
     * Try and use the cached access token if it exists,
     * otherwise get a new one from the authorization service.
     */
    public function authorize(): self
    {
        $prefix = config('euromonitor.cache.prefix', 'euromonitor');
        $cacheKey = "{$prefix}.access_token";
        $cachedToken = cache()->get($cacheKey);

        if ($cachedToken) {
            $this->accessToken = $cachedToken;

            return $this;
        }

        $token = $this->getToken();
        $this->accessToken = $token->access_token;

        cache()->put($cacheKey, $this->accessToken, now()->addSeconds($token->expires_in));

        return $this;
    }

    /**
     * Make a GET request to the API.
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function get(string $endpoint, array $query = []): object|array
    {
        $response = $this->authorize()
            ->request
            ->withToken($this->accessToken)
            ->get($this->baseUrl . '/' . $endpoint, $query);

        if (!$response->successful()) {
            throw new \Illuminate\Http\Client\RequestException($response);
        }

        return $response->object();
    }

    /**
     * Make a POST request to the API.
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function post(string $endpoint, array $data = []): object|array|null
    {
        $response = $this->authorize()
            ->request
            ->withToken($this->accessToken)
            ->post($this->baseUrl . '/' . $endpoint, $data);

        if (!$response->successful()) {
            throw new \Illuminate\Http\Client\RequestException($response);
        }

        return $response->object();
    }
}
