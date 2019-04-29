<?php

namespace Harmony\Sdk\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class ComponentExtension
 *
 * @package Harmony\Sdk\Twig
 */
class ComponentExtension extends AbstractExtension
{

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('component_extension', [$this, 'getComponent'])
        ];
    }

    public function getComponent(string $type)
    {
    }
}