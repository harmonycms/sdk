<?php

namespace Harmony\SDK\Receiver;

/**
 * Class Users
 *
 * @package Harmony\SDK\Receiver
 */
class Users extends AbstractReceiver
{

    /**
     * @return array
     */
    public function getUser(): array
    {
        return $this->getClient()->request('/users');
    }
}