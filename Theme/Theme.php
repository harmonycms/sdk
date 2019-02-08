<?php

namespace Harmony\Sdk\Theme;

/**
 * Class Theme
 *
 * @package Harmony\Sdk\Theme
 */
class Theme implements ThemeInterface
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
        if (null === $this->name) {
            $pos        = strrpos(static::class, '\\');
            $this->name = false === $pos ? static::class : substr(static::class, $pos + 1);
        }

        return $this->name;
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