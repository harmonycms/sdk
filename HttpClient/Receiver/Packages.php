<?php

namespace Harmony\Sdk\HttpClient\Receiver;

use Http\Client\Exception;

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
     * @throws Exception
     */
    public function getPackagesJson()
    {
        return $this->getClient()->get('/packages.json');
    }
}