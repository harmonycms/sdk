<?php

namespace Harmony\Sdk\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface BuildableInterface
 *
 * @package Harmony\Sdk\Extension
 */
interface BuildableInterface
{

    /**
     * Builds the extension.
     * It is only ever called once when the cache is empty.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container);
}