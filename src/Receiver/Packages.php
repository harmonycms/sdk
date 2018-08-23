<?php

namespace Harmony\Sdk\Receiver;

/**
 * Class Packages
 *
 * @package Harmony\Sdk\Receiver
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