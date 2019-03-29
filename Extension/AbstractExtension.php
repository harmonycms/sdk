<?php

namespace Harmony\Sdk\Extension;

use Exception;
use ReflectionClassConstant;
use ReflectionObject;
use function array_slice;
use function dirname;
use function explode;
use function file_get_contents;
use function implode;
use function is_subclass_of;
use function json_decode;
use function strrpos;
use function substr;

/**
 * Class AbstractExtension
 *
 * @package Harmony\Sdk\Extension
 */
abstract class AbstractExtension implements ExtensionInterface
{

    /** Constants */
    public const COMPONENT = 'component';
    public const MODULE    = 'module';
    public const PLUGIN    = 'plugin';

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

    /** @var string $homepage */
    protected $homepage;

    /** @var string $version */
    protected $version;

    /** @var array $authors */
    protected $authors;

    /** @var string $extensionType */
    private $extensionType;

    /**
     * AbstractExtension constructor.
     */
    public function __construct()
    {
        $pos              = strrpos(static::class, '\\');
        $this->identifier = false === $pos ? static::class : substr(static::class, $pos + 1);
        $this->path       = dirname((new ReflectionObject($this))->getFileName());
        $this->shortName  = implode(DIRECTORY_SEPARATOR,
            array_slice(explode(DIRECTORY_SEPARATOR, $this->path), - 2, 2));

        $composer          = $this->_parseComposer();
        $this->name        = $composer['name'];
        $this->description = $composer['description'] ?? '';
        $this->homepage    = $composer['homepage'] ?? '';
        $this->version     = $composer['version'] ?? '';
        $this->authors     = $composer['authors'] ?? [];

        if (is_subclass_of($this, Component::class)) {
            $this->extensionType = self::COMPONENT;
        } elseif (is_subclass_of($this, Module::class)) {
            $this->extensionType = self::MODULE;
        } elseif (is_subclass_of($this, Plugin::class)) {
            $this->extensionType = self::PLUGIN;
        }
    }

    /**
     * Returns the type of extension (component, module or plugin).
     *
     * @return string|null
     */
    final public function getExtensionType(): ?string
    {
        return $this->extensionType;
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
            $reflexionConstant = new ReflectionClassConstant($this, 'NAME');
            $this->name        = $reflexionConstant->getValue();
        }
        catch (Exception $e) {
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
            $reflexionConstant = new ReflectionClassConstant($this, 'DESCRIPTION');
            $this->description = $reflexionConstant->getValue();
        }
        catch (Exception $e) {
        }

        return $this->description;
    }

    /**
     * Returns the extension homepage.
     *
     * @return string
     */
    final public function getHomepage(): string
    {
        return $this->homepage;
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

    /**
     * Gets the Extension directory path.
     * The path should always be returned as a Unix path (with /).
     *
     * @return string The Extension absolute path
     */
    final public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Parse content of `composer.json` file.
     * Always present in all extensions, no need to check if file exists.
     *
     * @return array
     */
    private function _parseComposer(): array
    {
        return json_decode(file_get_contents($this->path . DIRECTORY_SEPARATOR . 'composer.json'), true);
    }
}