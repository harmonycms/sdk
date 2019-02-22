<?php

namespace Harmony\Sdk\Extension;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class Plugin
 *
 * @package Harmony\Sdk\Extension
 */
abstract class Plugin extends AbstractExtension implements ContainerAwareInterface, ContainerExtensionInterface, BootableInterface
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
}