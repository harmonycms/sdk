<?php

namespace Harmony\Sdk\Extension;

/**
 * Class AbstractExtension
 *
 * @package Harmony\Sdk\Extension
 */
abstract class AbstractExtension implements ExtensionInterface
{

    /** @var string $identifier */
    protected $identifier;

    /** @var string $path */
    protected $path;

    /** @var string $shortName */
    protected $shortName;

    /** @var string $name */
    protected $name;

    /** @var string $description */
    protected $description;

    /** @var string $version */
    protected $version;

    /** @var array $authors */
    protected $authors;

    /**
     * AbstractExtension constructor.
     */
    public function __construct()
    {
        $pos              = \strrpos(static::class, '\\');
        $this->identifier = false === $pos ? static::class : \substr(static::class, $pos + 1);
        $this->path       = \dirname((new \ReflectionObject($this))->getFileName());
        $this->shortName  = implode(DIRECTORY_SEPARATOR,
            array_slice(explode(DIRECTORY_SEPARATOR, $this->path), - 2, 2));

        $composer          = $this->_parseComposer();
        $this->name        = $composer['name'];
        $this->description = $composer['description'] ?? '';
        $this->version     = $composer['version'] ?? '';
        $this->authors     = $composer['authors'] ?? [];
    }

    /**
     * Returns the extension identifier.
     *
     * @return string The Extension name
     */
    final public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Returns extension short name, formatted has: vendor/name
     *
     * @return string
     */
    final public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * Returns the extension name.
     *
     * @return string The Extension name
     */
    final public function getName(): string
    {
        try {
            $reflexionConstant = new \ReflectionClassConstant($this, 'NAME');
            $this->name        = $reflexionConstant->getValue();
        }
        catch (\Exception $e) {
        }

        return $this->name;
    }

    /**
     * Returns the extension description.
     *
     * @return string The Extension description
     */
    final public function getDescription(): string
    {
        try {
            $reflexionConstant = new \ReflectionClassConstant($this, 'DESCRIPTION');
            $this->description = $reflexionConstant->getValue();
        }
        catch (\Exception $e) {
        }

        return $this->description;
    }

    /**
     * Returns the extension version.
     *
     * @return string
     */
    final public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Returns the extension authors.
     *
     * @return array
     */
    final public function getAuthors(): array
    {
        return $this->authors;
    }
}