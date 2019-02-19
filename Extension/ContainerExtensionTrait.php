<?php

namespace Harmony\Sdk\Extension;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Trait ContainerExtensionTrait
 *
 * @package Harmony\Sdk\Extension
 */
trait ContainerExtensionTrait
{

    /** @var ExtensionInterface|null */
    protected $extension;

    /** @var string $namespace */
    protected $namespace;

    /**
     * Gets the Extension namespace.
     *
     * @return string The Extension namespace
     */
    final public function getNamespace(): string
    {
        if (null === $this->namespace) {
            $pos             = strrpos(static::class, '\\');
            $this->namespace = false === $pos ? '' : substr(static::class, 0, $pos);
        }

        return $this->namespace;
    }

    /**
     * Returns the container extension that should be implicitly loaded.
     *
     * @return ExtensionInterface|null The default extension or null if there is none
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $extension = $this->createContainerExtension();

            if (null !== $extension) {
                if (!$extension instanceof ExtensionInterface) {
                    throw new \LogicException(sprintf('Extension %s must implement Symfony\Component\DependencyInjection\Extension\ExtensionInterface.',
                        \get_class($extension)));
                }

                // check naming convention
                $basename      = str_replace(['Component', 'Module', 'Plugin'], ['', '', ''], $this->getIdentifier());
                $expectedAlias = Container::underscore($basename);

                if ($expectedAlias != $extension->getAlias()) {
                    throw new \LogicException(sprintf('Users will expect the alias of the default extension of a bundle to be the underscored version of the bundle name ("%s"). You can override "Bundle::getContainerExtension()" if you want to use "%s" or another alias.',
                        $expectedAlias, $extension->getAlias()));
                }

                $this->extension = $extension;
            }
        }

        return $this->extension;
    }

    /**
     * Creates the bundle's container extension.
     *
     * @return ExtensionInterface|null
     */
    protected function createContainerExtension(): ?ExtensionInterface
    {
        if (class_exists($class = $this->getContainerExtensionClass())) {
            return new $class();
        }

        return null;
    }

    /**
     * Returns the extension's container extension class.
     *
     * @return string
     */
    protected function getContainerExtensionClass()
    {
        $basename = str_replace(['Component', 'Module', 'Plugin'], ['', '', ''], $this->getIdentifier());

        return $this->getNamespace() . '\\DependencyInjection\\' . $basename . 'Extension';
    }
}