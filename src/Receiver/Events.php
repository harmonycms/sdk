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
     * Ping API.
     *
     * @return array
     * @throws \Http\Client\Exception
     */
    public function ping(): array
    {
        return $this->getClient()->get('/ping');
    }

    /**
     * Get status for the given token.
     *
     * @param string $token
     *
     * @return array
     * @throws \Http\Client\Exception
     */
    public function tokenStatus(string $token): array
    {
        return $this->getClient()->get('/token/status', ['token' => $token]);
    }
}