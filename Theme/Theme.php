<?php

namespace Harmony\Sdk\Theme;

/**
 * Class Theme
 *
 * @package Harmony\Sdk\Theme
 */
abstract class Theme implements ThemeInterface
{

    /** @var string $name */
    protected $name;

    /** @var string $path */
    protected $path;

    /**
     * Returns the theme name (the class short name).
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

        if (null === $this->name) {
            $pos        = strrpos(static::class, '\\');
            $this->name = false === $pos ? static::class : substr(static::class, $pos + 1);
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

            return $reflexionConstant->getValue();
        }
        catch (\Exception $e) {
        }

        return '';
    }

    /**
     * Returns the theme preview image.
     *
     * @return string The theme preview image
     */
    public function getPreview(): string
    {
        $array = glob($this->getPath() . '/assets/images/preview.{jpg,jpeg,png,gif}', GLOB_BRACE);
        if (isset($array[0])) {
            return sprintf('/themes/%s/%s', '', (new \SplFileInfo($array[0]))->getBasename());
        }

        return '';
    }

    /**
     * Gets the Theme directory path.
     * The path should always be returned as a Unix path (with /).
     *
     * @return string The Theme absolute path
     */
    public function getPath(): string
    {
        if (null === $this->path) {
            $this->path = \dirname((new \ReflectionObject($this))->getFileName());
        }

        return $this->path;
    }
}