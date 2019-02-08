<?php

namespace Harmony\Sdk\HttpClient\Receiver;

use Http\Client\Exception;

/**
 * Class Projects
 *
 * @package Harmony\Sdk\Receiver
 */
class Projects
{

    use Receiver;

    /**
     * @return array|string
     * @throws Exception
     */
    public function getProjects()
    {
        return $this->getClient()->get('/projects');
    }

    /**
     * @param string $projectId
     *
     * @return array|string
     * @throws Exception
     */
    public function getProject(string $projectId)
    {
        return $this->getClient()->get('/projects/' . $projectId);
    }
}