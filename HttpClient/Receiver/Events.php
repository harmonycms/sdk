<?php

namespace Harmony\Sdk\HttpClient\Receiver;

use Http\Client\Exception;

/**
 * Class Events
 *
 * @package Harmony\Sdk\Receiver
 */
class Events
{

    use Receiver;

    /**
     * Ping API.
     *
     * @return array
     * @throws Exception
     */
    public function ping(): array
    {
        return (array)$this->getClient()->get('/ping');
    }

    /**
     * Get status for the given token.
     *
     * @param string $token
     *
     * @return array
     * @throws Exception
     */
    public function tokenStatus(string $token): array
    {
        return $this->getClient()->get('/token/status', ['token' => $token]);
    }
}