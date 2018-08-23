<?php

namespace Harmony\Sdk\Receiver;

/**
 * Class Users
 *
 * @package Harmony\Sdk\Receiver
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