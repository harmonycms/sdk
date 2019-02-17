<?php

namespace Harmony\Sdk\Theme;

/**
 * Class Theme
 *
 * @package Harmony\Sdk\Theme
 */
abstract class Theme implements ThemeInterface
{

    /** @var string $identifier */
    protected $identifier;

    /** @var string $name */
    protected $name;

    /** @var string $description */
    protected $description;

    /** @var string $version */
    protected $version;

    /** @var array $authors */
    protected $authors = [];

    /** @var string $path */
    protected $path;

    /** @var string $shortName */
    protected $shortName;

    /** @var string $settingPath */
    protected $settingPath;

    /**
     * Theme constructor.
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

        $this->settingPath = $this->path . DIRECTORY_SEPARATOR . 'settings.yaml';
    }

    /**
     * Returns the theme identifier.
     *
     * @return string The Theme name
     */
    final public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Returns theme short name, formatted has: vendor/name
     *
     * @return string
     */
    final public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * Returns the theme name.
     *
     * @return string The Theme name
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
     * Returns the theme description.
     *
     * @return string The Theme description
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
     * Returns the theme version.
     *
     * @return string
     */
    final public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Returns the theme authors.
     *
     * @return array
     */
    final public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * Check if the theme has settings.
     *
     * @return bool
     */
    final public function hasSettings(): bool
    {
        return \file_exists($this->settingPath);
    }

    /**
     * Returns the full path for `settings.yaml` file.
     *
     * @return string
     */
    final public function getSettingPath(): string
    {
        return $this->settingPath;
    }

    /**
     * Gets the Theme directory path.
     * The path should always be returned as a Unix path (with /).
     *
     * @return string The Theme absolute path
     */
    final public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Returns the theme preview image.
     *
     * @return null|string The theme preview image
     */
    public function getPreview(): ?string
    {
        $array = \glob($this->getPath() . '/assets/images/preview.{jpg,jpeg,png,gif}', GLOB_BRACE);
        if (isset($array[0])) {
            return sprintf('/themes/%s/%s', '', (new \SplFileInfo($array[0]))->getBasename());
        }

        return null;
    }

    /**
     * Get parent theme (FQDN class).
     *
     * @return string
     */
    public function getParent(): string
    {
        return '';
    }

    /**
     * Parse content of `composer.json` file.
     * Always present in all themes, no need to check if file exists.
     *
     * @return array
     */
    private function _parseComposer(): array
    {
        return \json_decode(\file_get_contents($this->path . DIRECTORY_SEPARATOR . 'composer.json'), true);
    }
}