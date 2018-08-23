<?php

namespace Harmony\Sdk\Receiver;

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
     * @throws \Http\Client\Exception
     */
    public function getProjects()
    {
        return $this->getClient()->get('/projects');
    }

    /**
     * @param string $projectId
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public function getProject(string $projectId)
    {
        return $this->getClient()->get('/projects/' . $projectId);
    }
}