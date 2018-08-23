<?php

namespace Harmony\SDK\Receiver;

/**
 * Class Events
 *
 * @package Harmony\SDK\Receiver
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