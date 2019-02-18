<?php

namespace Harmony\Sdk\Extension;

/**
 * Class Component
 *
 * @package Harmony\Sdk\Extension
 */
abstract class Component extends AbstractExtension
{

    /** @var string $type */
    protected $type;

    /**
     * @return string
     */
    final public function getType(): string
    {
        return $this->type;
    }
}