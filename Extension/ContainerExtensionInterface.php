<?php

namespace Harmony\Sdk\Extension;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Interface ContainerExtensionInterface
 *
 * @package Harmony\Sdk\Extension
 */
interface ContainerExtensionInterface
{

    /**
     * Returns the container extension that should be implicitly loaded.
     *
     * @return ExtensionInterface|null The default extension or null if there is none
     */
    public function getContainerExtension(): ?ExtensionInterface;
}