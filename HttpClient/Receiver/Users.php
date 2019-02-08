<?php

namespace Harmony\Sdk\HttpClient\Receiver;

use Http\Client\Exception;

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
     * @throws Exception
     */
    public function getUser(): array
    {
        return $this->getClient()->get('/users');
    }
}