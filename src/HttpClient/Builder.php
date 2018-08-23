<?php

namespace Harmony\SDK\HttpClient;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClientFactory;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;

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
     * A HTTP client with all our plugins.
     *
     * @var HttpMethodsClient $pluginClient
     */
    protected $pluginClient;

    /**
     * True if we should create a new Plugin client at next request.
     *
     * @var bool $httpClientModified
     */
    protected $httpClientModified = true;

    /** @var array $plugins */
    protected $plugins = [];

    /** @var MessageFactory $requestFactory */
    protected $requestFactory;

    /** @var StreamFactory $streamFactory */
    protected $streamFactory;

    /**
     * Http headers.
     *
     * @var array $headers
     */
    protected $headers = [];

    /**
     * Builder constructor.
     *
     * @param HttpClient|null     $httpClient
     * @param RequestFactory|null $requestFactory
     * @param StreamFactory|null  $streamFactory
     */
    public function __construct(HttpClient $httpClient = null, RequestFactory $requestFactory = null,
                                StreamFactory $streamFactory = null)
    {
        $this->httpClient     = $httpClient ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
        $this->streamFactory  = $streamFactory ?: StreamFactoryDiscovery::find();
    }

    /**
     * Get httpClient
     *
     * @return HttpMethodsClient
     */
    public function getHttpClient(): HttpMethodsClient
    {
        if ($this->httpClientModified) {
            $this->httpClientModified = false;
            $plugins                  = $this->plugins;
            $this->pluginClient
                                      = new HttpMethodsClient((new PluginClientFactory())->createClient($this->httpClient,
                $plugins), $this->requestFactory);
        }

        return $this->pluginClient;
    }

    /**
     * Add a new plugin to the end of the plugin chain.
     *
     * @param Plugin $plugin
     *
     * @return Builder
     */
    public function addPlugin(Plugin $plugin): Builder
    {
        $this->plugins[]          = $plugin;
        $this->httpClientModified = true;

        return $this;
    }

    /**
     * Remove a plugin by its fully qualified class name (FQCN).
     *
     * @param string $fqcn
     *
     * @return Builder
     */
    public function removePlugin($fqcn): Builder
    {
        foreach ($this->plugins as $idx => $plugin) {
            if ($plugin instanceof $fqcn) {
                unset($this->plugins[$idx]);
                $this->httpClientModified = true;
            }
        }

        return $this;
    }

    /**
     * Clears used headers.
     *
     * @return Builder
     */
    public function clearHeaders(): Builder
    {
        $this->headers = [];
        $this->removePlugin(Plugin\HeaderAppendPlugin::class);
        $this->addPlugin(new Plugin\HeaderAppendPlugin($this->headers));

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return Builder
     */
    public function addHeaders(array $headers): Builder
    {
        $this->headers = array_merge($this->headers, $headers);
        $this->removePlugin(Plugin\HeaderAppendPlugin::class);
        $this->addPlugin(new Plugin\HeaderAppendPlugin($this->headers));

        return $this;
    }

    /**
     * @param string $header
     * @param string $headerValue
     *
     * @return Builder
     */
    public function addHeaderValue($header, $headerValue): Builder
    {
        if (!isset($this->headers[$header])) {
            $this->headers[$header] = $headerValue;
        } else {
            $this->headers[$header] = array_merge((array)$this->headers[$header], [$headerValue]);
        }
        $this->removePlugin(Plugin\HeaderAppendPlugin::class);
        $this->addPlugin(new Plugin\HeaderAppendPlugin($this->headers));

        return $this;
    }
}