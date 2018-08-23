<?php

namespace Harmony\SDK;

use Exception;
use Harmony\SDK\HttpClient\Builder;
use Harmony\SDK\HttpClient\Message\ResponseMediator;
use Harmony\SDK\HttpClient\Plugin\Authentication;
use Harmony\SDK\HttpClient\Plugin\History;
use Harmony\SDK\Receiver\AbstractReceiver;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\HttpClient;
use Http\Discovery\UriFactoryDiscovery;
use InvalidArgumentException;

/**
 * Class Client
 *
 * @package Harmony\SDK
 */
class Client extends AbstractApi
{

    /** Receiver constants */
    const RECEIVER_USERS = 'Users';

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
    const AUTH_JWT = 'jwt';

    /** @var Builder $httpClientBuilder */
    protected $httpClientBuilder;

    /** @var History $responseHistory */
    protected $responseHistory;

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
            ->createUri(AbstractApi::API_URL)));
        $this->httpClientBuilder->addPlugin(new Plugin\HeaderDefaultsPlugin([
            'User-Agent' => 'harmony-sdk (https://github.com/projectharmony/sdk)',
        ]));
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
     * Authenticate a user for all next requests.
     *
     * @param string      $tokenOrLogin GitHub private token/username/client ID
     * @param null|string $password     GitHub password/secret (optionally can contain $authMethod)
     * @param null|string $authMethod   One of the AUTH_* class constants
     *
     * @throws InvalidArgumentException If no authentication method was given
     */
    public function authenticate($tokenOrLogin, $password = null, $authMethod = null)
    {
        if (null === $password && null === $authMethod) {
            throw new InvalidArgumentException('You need to specify authentication method!');
        }
        if (null === $authMethod && in_array($password, [
                self::AUTH_URL_TOKEN,
                self::AUTH_URL_CLIENT_ID,
                self::AUTH_HTTP_PASSWORD,
                self::AUTH_HTTP_TOKEN,
                self::AUTH_JWT
            ])) {
            $authMethod = $password;
            $password   = null;
        }
        if (null === $authMethod) {
            $authMethod = self::AUTH_HTTP_PASSWORD;
        }
        $this->getHttpClientBuilder()->removePlugin(Authentication::class);
        $this->getHttpClientBuilder()->addPlugin(new Authentication($tokenOrLogin, $password, $authMethod));
    }

    /**
     * Returns receiver object
     *
     * @param string $receiver
     *
     * @return null|AbstractReceiver
     * @throws Exception
     */
    public function getReceiver(string $receiver): ?AbstractReceiver
    {
        $class = (string)$this->sprintf(':namespace\Receiver\:receiver', __NAMESPACE__, $receiver);
        if (class_exists($class)) {
            return new $class($this);
        }

        return null;
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
    public function getHttpClient(): HttpMethodsClient
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * @param string $path
     * @param array  $parameters
     * @param array  $requestHeaders
     *
     * @return mixed
     */
    public function request(string $path, array $parameters = [], array $requestHeaders = [])
    {
        $response = $this->getHttpClient()->get($path, $requestHeaders);

        return ResponseMediator::getContent($response);
    }

}