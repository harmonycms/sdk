<?php

namespace Harmony\Sdk\Extension;

/**
 * Interface ExtensionInterface
 *
 * @package Harmony\Sdk\Extension
 */
interface ExtensionInterface
{

    /**
     * Returns the type of extension (component, module or plugin).
     *
     * @return string|null
     */
    public function getExtensionType(): ?string;

    /**
     * Returns the extension name.
     *
     * @return string The Extension name
     */
    public function getIdentifier(): string;

    /**
     * Returns the extension name.
     *
     * @return string The Extension name
     */
    public function getName(): string;

    /**
     * Returns the extension description.
     *
     * @return string The Extension description
     */
    public function getDescription(): string;

    /**
     * Returns the extension version.
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Returns the extension authors.
     *
     * @return array
     */
    public function getAuthors(): array;

    /**
     * Gets the Extension directory path.
     * The path should always be returned as a Unix path (with /).
     *
     * @return string The Extension absolute path
     */
    public function getPath(): string;

    /**
     * Returns extension short name, formatted has: vendor/name
     *
     * @return string
     */
    public function getShortName(): string;
}