<?php

namespace Harmony\Sdk\Extension;

/**
 * Interface BootableInterface
 *
 * @package Harmony\Sdk\Extension
 */
interface BootableInterface
{

    /**
     * Boots the Extension.
     *
     * @return void
     */
    public function boot(): void;
}