<?php

namespace Harmony\Sdk\Extension;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class Module
 *
 * @package Harmony\Sdk\Extension
 */
abstract class Module extends AbstractExtension implements ContainerAwareInterface
{

    use ContainerAwareTrait;
}