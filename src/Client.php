<?php

namespace Harmony\SDK;

use Exception;
use Http\Client\HttpClient;
use Harmony\SDK\HttpClient\Builder;
use Harmony\SDK\HttpClient\Message\ResponseMediator;
use Harmony\SDK\HttpClient\Plugin\Authentication;
use Harmony\SDK\HttpClient\Plugin\History;
use Harmony\SDK\Receiver\Receiver;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Discovery\UriFactoryDiscovery;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 *
 * @package Harmony\SDK
 */
class Client
{

    /** API constants */
    const API_URL    = 'https://api.harmonycms.net';
    const USER_AGENT = 'harmony-sdk (https://github.com/projectharmony/sdk)';

    /** Receiver constants */
    const RECEIVER_EVENTS   = 'Events';
    const RECEIVER_PACKAGES = 'Packages';
    const RECEIVER_PROJECTS = 'Projects';
    const RECEIVER_USERS    = 'Users';

    /**
     * Constant for authentication method. Indicates the default, but deprecated
     * login with username and token in URL.
     */
    const AUTH_URL_TOKEN = 'url_token';
    /**
     * Constant for authentication method. Not indicates the new login, but allows
     * usage of unauthenticated rate limited requests for given client_id + client_secret.
     */
    const AUTH_URL_CLIENT_ID = 'url_client_id';
    /**
     * Constant for authentication method. Indicates the new favored login method
     * with username and password via HTTP Authentication.
     */
    const AUTH_HTTP_PASSWORD = 'http_password';
    /**
     * Constant for authentication method. Indicates the new login method with
     * with username and token via HTTP Authentication.
     */
    const AUTH_HTTP_TOKEN = 'http_token';
    /**
     * Constant for authentication method. Indicates JSON Web Token
     * authentication required for integration access to the API.
     */
    const AUTH_BEARER = 'bearer';

    /** @var Builder $httpClientBuilder */
    protected $httpClientBuilder;

    /** @var History $responseHistory */
    protected $responseHistory;

    /** @var null|int $page */
    protected $page;

    /** @var null|int $perPage */
    protected $perPage;

    /**
     * Client constructor.
     *
     * @param Builder|null $httpClientBuilder
     */
    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->responseHistory   = new History();
        $this->httpClientBuilder = $httpClientBuilder ?: new Builder();

        $this->httpClientBuilder->addPlugin(new Plugin\HistoryPlugin($this->responseHistory));
        $this->httpClientBuilder->addPlugin(new Plugin\RedirectPlugin());
        $this->httpClientBuilder->addPlugin(new Plugin\AddHostPlugin(UriFactoryDiscovery::find()
            ->createUri(self::API_URL)));
        $this->httpClientBuilder->addPlugin(new Plugin\HeaderDefaultsPlugin(['User-Agent' => self::USER_AGENT,]));
    }

    /**
     * Create a Github\Client using a HttpClient.
     *
     * @param HttpClient $httpClient
     *
     * @return Client
     */
    public static function createWithHttpClient(HttpClient $httpClient): Client
    {
        return new self(new Builder($httpClient));
    }

    /**
     * Returns receiver object
     *
     * @param string $receiver
     *
     * @return null|Receiver
     * @throws Exception
     */
    public function getReceiver(string $receiver)
    {
        $class = (string)$this->sprintf(':namespace\Receiver\:receiver', __NAMESPACE__, $receiver);
        if (class_exists($class)) {
            return new $class($this);
        }

        return null;
    }

    /**
     * @param string $token
     *
     * @return Client
     */
    public function setBearerToken(string $token): Client
    {
        $this->authenticate($token, null, self::AUTH_BEARER);

        return $this;
    }

    /**
     * Authenticate a user for all next requests.
     *
     * @param string      $tokenOrLogin GitHub private token/username/client ID
     * @param null|string $password     GitHub password/secret (optionally can contain $authMethod)
     * @param null|string $authMethod   One of the AUTH_* class constants
     *
     * @return Client
     * @throws InvalidArgumentException If no authentication method was given
     */
    public function authenticate($tokenOrLogin, $password = null, $authMethod = null): Client
    {
        if (null === $password && null === $authMethod) {
            throw new InvalidArgumentException('You need to specify authentication method!');
        }
        if (null === $authMethod && in_array($password, [
                self::AUTH_URL_TOKEN,
                self::AUTH_URL_CLIENT_ID,
                self::AUTH_HTTP_PASSWORD,
                self::AUTH_HTTP_TOKEN,
                self::AUTH_BEARER
            ])) {
            $authMethod = $password;
            $password   = null;
        }
        if (null === $authMethod) {
            $authMethod = self::AUTH_HTTP_PASSWORD;
        }
        $this->getHttpClientBuilder()->removePlugin(Authentication::class);
        $this->getHttpClientBuilder()->addPlugin(new Authentication($tokenOrLogin, $password, $authMethod));

        return $this;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path
     * @param array  $parameters
     * @param array  $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public function get(string $path, array $parameters = [], array $requestHeaders = [])
    {
        if (null !== $this->page && !isset($parameters['page'])) {
            $parameters['page'] = $this->page;
        }
        if (null !== $this->perPage && !isset($parameters['per_page'])) {
            $parameters['per_page'] = $this->perPage;
        }
        if (array_key_exists('ref', $parameters) && is_null($parameters['ref'])) {
            unset($parameters['ref']);
        }
        if (count($parameters) > 0) {
            $path .= '?' . http_build_query($parameters);
        }

        $response = $this->getHttpClient()->get($path, $requestHeaders);

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a HEAD request with query parameters.
     *
     * @param       $path
     * @param array $parameters
     * @param array $requestHeaders
     *
     * @return ResponseInterface
     * @throws \Http\Client\Exception
     */
    public function head($path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        if (array_key_exists('ref', $parameters) && is_null($parameters['ref'])) {
            unset($parameters['ref']);
        }
        $response = $this->getHttpClient()->head($path . '?' . http_build_query($parameters), $requestHeaders);

        return $response;
    }

    /**
     * @param       $path
     * @param array $parameters
     * @param array $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public function post($path, array $parameters = [], array $requestHeaders = [])
    {
        return $this->postRaw($path, $this->createJsonBody($parameters), $requestHeaders);
    }

    /**
     * @param       $path
     * @param       $body
     * @param array $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public function postRaw($path, $body, array $requestHeaders = [])
    {
        $response = $this->getHttpClient()->post($path, $requestHeaders, $body);

        return ResponseMediator::getContent($response);
    }

    /**
     * @param       $path
     * @param array $parameters
     * @param array $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public function patch($path, array $parameters = [], array $requestHeaders = [])
    {
        $response = $this->getHttpClient()->patch($path, $requestHeaders, $this->createJsonBody($parameters));

        return ResponseMediator::getContent($response);
    }

    /**
     * @param       $path
     * @param array $parameters
     * @param array $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public function put($path, array $parameters = [], array $requestHeaders = [])
    {
        $response = $this->getHttpClient()->put($path, $requestHeaders, $this->createJsonBody($parameters));

        return ResponseMediator::getContent($response);
    }

    /**
     * @param       $path
     * @param array $parameters
     * @param array $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public function delete($path, array $parameters = [], array $requestHeaders = [])
    {
        $response = $this->getHttpClient()->delete($path, $requestHeaders, $this->createJsonBody($parameters));

        return ResponseMediator::getContent($response);
    }

    /**
     * @return Builder
     */
    protected function getHttpClientBuilder(): Builder
    {
        return $this->httpClientBuilder;
    }

    /**
     * @return HttpMethodsClient
     */
    protected function getHttpClient(): HttpMethodsClient
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * Return a formatted string. Modified version of sprintf using colon(:)
     *
     * @param string $string
     * @param array  $params
     *
     * @return String
     * @throws Exception
     */
    protected function sprintf(string $string, ...$params): string
    {
        preg_match_all('/\:([A-Za-z0-9_]+)/', $string, $matches);
        $matches = $matches[1];
        if (count($matches)) {
            $tokens   = [];
            $replaces = [];
            foreach ($matches as $key => $value) {
                if (count($params) > 1 || !is_array($params[0])) {
                    if (!array_key_exists($key, $params)) {
                        throw new Exception('Too few arguments, missing argument: ' . $key);
                    }
                    $replaces[] = $params[$key];
                } else {
                    if (!array_key_exists($value, $params[0])) {
                        throw new Exception('Missing array argument: ' . $key);
                    }
                    $replaces[] = $params[0][$value];
                }
                $tokens[] = ':' . $value;
            }
            $string = str_replace($tokens, $replaces, $string);
        }

        return $string;
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $parameters Request parameters
     *
     * @return null|string
     */
    protected function createJsonBody(array $parameters): ?string
    {
        return (count($parameters) === 0) ? null : json_encode($parameters, empty($parameters) ? JSON_FORCE_OBJECT : 0);
    }
}