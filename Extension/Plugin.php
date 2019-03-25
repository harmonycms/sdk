<?php

namespace Harmony\Sdk\Extension;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class Plugin
 *
 * @package Harmony\Sdk\Extension
 */
abstract class Plugin extends AbstractExtension implements ContainerAwareInterface, ContainerExtensionInterface, BootableInterface, BuildableInterface
{

    use ContainerAwareTrait;
    use ContainerExtensionTrait;

    /**
     * Boots the Extension.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Builds the extension.
     * It is only ever called once when the cache is empty.
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
    }
}