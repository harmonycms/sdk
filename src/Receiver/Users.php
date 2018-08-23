<?php

namespace Harmony\SDK\Receiver;

/**
 * Class Users
 *
 * @package Harmony\SDK\Receiver
 */
class Users
{

    use Receiver;

    /**
     * @return array
     * @throws \Http\Client\Exception
     */
    public function getUser(): array
    {
        return $this->getClient()->get('/users');
    }
}