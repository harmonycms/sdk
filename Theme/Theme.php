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

    /** @var string $path */
    protected $path;

    /**
     * Theme constructor.
     */
    public function __construct()
    {
        $pos              = strrpos(static::class, '\\');
        $this->identifier = false === $pos ? static::class : substr(static::class, $pos + 1);
        $this->path       = \dirname((new \ReflectionObject($this))->getFileName());
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
     * Returns the theme name.
     *
     * @return string The Theme name
     */
    final public function getName(): string
    {
        try {
            $reflexionConstant = new \ReflectionClassConstant($this, 'NAME');

            return $reflexionConstant->getValue();
        }
        catch (\Exception $e) {
        }

        return $this->getIdentifier();
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

            return $reflexionConstant->getValue();
        }
        catch (\Exception $e) {
        }

        return '';
    }

    /**
     * Returns the theme preview image.
     *
     * @return null|string The theme preview image
     */
    public function getPreview(): ?string
    {
        $array = glob($this->getPath() . '/assets/images/preview.{jpg,jpeg,png,gif}', GLOB_BRACE);
        if (isset($array[0])) {
            return sprintf('/themes/%s/%s', '', (new \SplFileInfo($array[0]))->getBasename());
        }

        return null;
    }

    /**
     * Gets the Theme directory path.
     * The path should always be returned as a Unix path (with /).
     *
     * @return string The Theme absolute path
     */
    public function getPath(): string
    {
        return $this->path;
    }
}