<?php

namespace Harmony\SDK\Receiver;

use Harmony\SDK\Client;

/**
 * Trait Receiver
 *
 * @package Harmony\SDK\Receiver
 */
trait Receiver
{

    /** @var Client $client */
    private $client;

    /**
     * Receiver constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     *
     * @return Receiver
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}