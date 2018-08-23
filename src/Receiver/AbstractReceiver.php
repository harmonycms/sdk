<?php

namespace Harmony\SDK\Receiver;

use Harmony\SDK\Client;

/**
 * Class AbstractReceiver
 *
 * @package Harmony\SDK\Receiver
 */
abstract class AbstractReceiver
{

    /** @var  mixed */
    protected $client;

    /**
     * AbstractReceiver constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $client
     *
     * @return AbstractReceiver
     */
    public function setClient($client): AbstractReceiver
    {
        $this->client = $client;

        return $this;
    }
}