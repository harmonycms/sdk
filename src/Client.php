<?php

namespace Harmony\SDK;

use Harmony\SDK\HttpClient\Builder;

/**
 * Class Client
 *
 * @package Harmony\SDK
 */
class Client
{

    /** @var Builder $httpClientBuilder */
    protected $httpClientBuilder;

    /**
     * Client constructor.
     *
     * @param Builder|null $httpClientBuilder
     */
    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $httpClientBuilder ?: new Builder();
    }
}