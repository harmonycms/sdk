<?php

namespace Harmony\Sdk\Exception;

use RuntimeException;

/**
 * ApiLimitExceedException.
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class ApiLimitExceedException extends RuntimeException
{

    private $limit;

    private $reset;

    /**
     * ApiLimitExceedException constructor.
     *
     * @param int  $limit
     * @param int  $reset
     * @param int  $code
     * @param null $previous
     */
    public function __construct($limit = 5000, $reset = 1800, $code = 0, $previous = null)
    {
        $this->limit = (int)$limit;
        $this->reset = (int)$reset;
        parent::__construct(sprintf('You have reached GitHub hourly limit! Actual limit is: %d', $limit), $code,
            $previous);
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getResetTime(): int
    {
        return $this->reset;
    }
}