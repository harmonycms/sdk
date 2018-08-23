<?php

namespace Harmony\SDK\Receiver;

/**
 * Class Packages
 *
 * @package Harmony\SDK\Receiver
 */
class Packages
{

    use Receiver;

    /**
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public function getPackagesJson()
    {
        return $this->getClient()->get('/packages.json');
    }
}