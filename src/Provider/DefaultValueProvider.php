<?php

namespace LPhilippo\CastableFormRequest\Provider;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use LPhilippo\CastableFormRequest\Utils\ArrayUtils;

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
        $recursiveKeys = array_filter(
            array_keys($defaults),
            function ($key) {
                return Str::contains($key, '*');
            }
        );

        $defaultData = [];
        foreach (Arr::except($defaults, $recursiveKeys) as $key => $value) {
            // Undot all default, excluding the recursive keys.
            Arr::set($defaultData, $key, $value);
        }

        $data = ArrayUtils::deepMerge($defaultData, $data);

        if (count($recursiveKeys)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $applicableNestedDefaults = self::getApplicableNestedDefaults($key, Arr::only($defaults, $recursiveKeys));

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
