<?php

namespace LPhilippo\CastableFormRequest\Provider;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DefaultValueProvider
{
    /**
     * Apply nested default rules.
     *
     * @param array $data
     * @param array $defaults
     *
     * @return array
     */
    public static function apply(
        array $data,
        array $defaults
    ) {
        $dottedKeys = array_filter(
            array_keys($defaults),
            function ($key) {
                return Str::contains($key, '.');
            }
        );

        $data = array_replace(
            Arr::except($defaults, $dottedKeys),
            $data
        );

        if (count($dottedKeys)) {
            $data = self::addMissingKeys($data, $dottedKeys);

            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $applicableNestedDefaults = self::getApplicableNestedDefaults($key, Arr::only($defaults, $dottedKeys));

                    if (count($applicableNestedDefaults)) {
                        $data[$key] = self::apply($value, $applicableNestedDefaults);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Returns a filtered list of defaults, that can correspond with the provided key.
     *
     * @param string $key
     * @param array $defaults
     * @param string $param
     *
     * @return array
     */
    protected static function getApplicableNestedDefaults(string $param, array $defaults)
    {
        $applicableNestedDefaults = [];

        foreach ($defaults as $key => $value) {
            $keyParts = explode('.', $key);
            $firstKeyPart = Arr::first($keyParts);

            if ($firstKeyPart === '*' || $firstKeyPart === $param) {
                array_shift($keyParts);
                $applicableNestedDefaults[implode('.', $keyParts)] = $value;
            }
        }

        return $applicableNestedDefaults;
    }

    /**
     * Populate missing keys in the data array, based on dotted default keys.
     *
     * @param array $data
     * @param array $keys
     *
     * @return array
     */
    protected static function addMissingKeys($data, $keys)
    {
        foreach ($keys as $key) {
            $keyParts = explode('.', $key);
            $firstKeyPart = Arr::first($keyParts);

            if (!array_key_exists($firstKeyPart, $data)) {
                if ($firstKeyPart === '*') {
                    // Ignore. Wildcards do not force creation of keys.
                } elseif (count($keyParts) === 2) {
                    // There are no more nested levels.
                    $data[$firstKeyPart] = [];
                } else {
                    $data[$firstKeyPart] = self::addMissingKeys(
                        [],
                        [
                            implode('.', array_slice($keyParts, 1)),
                        ]
                    );
                }
            }
        }

        return $data;
    }
}
