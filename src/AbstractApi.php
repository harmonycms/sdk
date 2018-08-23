<?php

namespace Harmony\SDK;

use Exception;

/**
 * Class AbstractApi
 *
 * @package Harmony\SDK
 */
abstract class AbstractApi
{

    /** API constants */
    const API_URL = 'https://api.harmonycms.net';

    /**
     * Return a formatted string. Modified version of sprintf using colon(:)
     *
     * @param string $string
     * @param array  $params
     *
     * @return String
     * @throws Exception
     */
    protected function sprintf(string $string, ...$params): string
    {
        preg_match_all('/\:([A-Za-z0-9_]+)/', $string, $matches);
        $matches = $matches[1];
        if (count($matches)) {
            $tokens   = [];
            $replaces = [];
            foreach ($matches as $key => $value) {
                if (count($params) > 1 || !is_array($params[0])) {
                    if (!array_key_exists($key, $params)) {
                        throw new Exception('Too few arguments, missing argument: ' . $key);
                    }
                    $replaces[] = $params[$key];
                } else {
                    if (!array_key_exists($value, $params[0])) {
                        throw new Exception('Missing array argument: ' . $key);
                    }
                    $replaces[] = $params[0][$value];
                }
                $tokens[] = ':' . $value;
            }
            $string = str_replace($tokens, $replaces, $string);
        }

        return $string;
    }
}