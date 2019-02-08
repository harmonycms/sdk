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
     * Returns the theme name.
     *
     * @return string The Theme name
     */
    public function getName(): string;

    /**
     * Returns the theme description.
     *
     * @return string The Theme description
     */
    public function getDescription(): string;

    /**
     * Returns the theme preview image.
     *
     * @return string The theme preview image
     */
    public function getPreview(): string;

    /**
     * Gets the Theme directory path.
     * The path should always be returned as a Unix path (with /).
     *
     * @return string The Theme absolute path
     */
    public function getPath(): string;
}