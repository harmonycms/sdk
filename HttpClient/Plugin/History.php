<?php

namespace Harmony\Sdk\HttpClient\Plugin;

use Http\Client\Common\Plugin\Journal;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class History
 * A plugin to remember the last response.
 *
 * @package Harmony\Sdk\HttpClient\Plugin
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
     * @param RequestInterface         $request
     * @param ClientExceptionInterface $exception
     */
    public function addFailure(RequestInterface $request, ClientExceptionInterface $exception)
    {
    }
}