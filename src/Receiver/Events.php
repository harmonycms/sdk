<?php

namespace Harmony\Sdk\Receiver;

/**
 * Class Events
 *
 * @package Harmony\Sdk\Receiver
 */
class Events
{

    use Receiver;

    /**
     * @return array
     * @throws \Http\Client\Exception
     */
    public function ping(): array
    {
        return $this->getClient()->get('/ping');
    }
}