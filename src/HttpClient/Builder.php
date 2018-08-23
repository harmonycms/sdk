<?php

namespace Harmony\SDK\HttpClient;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;

/**
 * Class Builder
 *
 * @package Harmony\SDK\HttpClient
 */
class Builder
{

    /**
     * The object that sends HTTP messages.
     *
     * @var HttpClient $httpClient
     */
    protected $httpClient;

    /**
     * Builder constructor.
     *
     * @param HttpClient|null $httpClient
     */
    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
    }

    /**
     * Get httpClient
     *
     * @return HttpClient
     */
    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }
}