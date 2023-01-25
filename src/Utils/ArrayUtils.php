<?php

namespace LPhilippo\CastableFormRequest\Utils;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ArrayUtils extends BaseServiceProvider
{
    /**
     * Return the recursively merged array, with the values taken from given arrays.
     *
     * @return array
     */
    public static function deepMerge()
    {
        return self::deepMergeArrays(func_get_args());
    }

    /**
     * Based on Drupal 10.x implementation for deep-merging multiple arrays:.
     *
     *  https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Component%21Utility%21NestedArray.php/10.0.x
     *
     * @param array $arrays
     * @param bool $preserveIntegerKeys
     */
    protected static function deepMergeArrays(array $arrays, bool $preserveIntegerKeys = false)
    {
        $result = [];

        foreach ($arrays as $array) {
            $isZeroKeyedArray = Arr::first(array_keys($array)) === 0;

            foreach ($array as $key => $value) {
                // Renumber integer keys as array_merge_recursive() does unless
                // $preserve_integer_keys is set to TRUE. Note that PHP automatically
                // converts array keys that are integer strings (e.g., '1') to integers.
                if ($isZeroKeyedArray && is_int($key) && !$preserveIntegerKeys) {
                    $result[] = $value;
                } elseif (isset($result[$key]) && is_array($result[$key]) && is_array($value)) {
                    $result[$key] = self::deepMergeArrays([
                        $result[$key],
                        $value,
                    ], $preserveIntegerKeys);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}
