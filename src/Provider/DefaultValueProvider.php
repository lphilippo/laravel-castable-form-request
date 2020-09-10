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
}
