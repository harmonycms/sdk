<?php

namespace Harmony\Sdk\Theme;

/**
 * Interface ThemeInterface
 *
 * @package Harmony\Sdk\Theme
 */
interface ThemeInterface
{

    /**
     * Returns the theme name (the class short name).
     *
     * @return string The Theme name
     */
    public function getName(): string;

    /**
     * Gets the Theme directory path.
     * The path should always be returned as a Unix path (with /).
     *
     * @return string The Theme absolute path
     */
    public function getPath(): string;
}