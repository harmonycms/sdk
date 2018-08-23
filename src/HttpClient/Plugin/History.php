<?php

namespace Harmony\SDK\HttpClient\Plugin;

use Http\Client\Common\Plugin\Journal;
use Http\Client\Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class History
 * A plugin to remember the last response.
 *
 * @package Harmony\SDK\HttpClient\Plugin
 */
class History implements Journal
{

    /** @var ResponseInterface $lastResponse */
    private $lastResponse;

    /**
     * @return null|ResponseInterface
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->lastResponse;
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    public function addSuccess(RequestInterface $request, ResponseInterface $response)
    {
        $this->lastResponse = $response;
    }

    /**
     * @param RequestInterface $request
     * @param Exception        $exception
     */
    public function addFailure(RequestInterface $request, Exception $exception)
    {
    }
}