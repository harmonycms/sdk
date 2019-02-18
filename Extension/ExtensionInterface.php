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
}