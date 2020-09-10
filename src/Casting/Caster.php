<?php

namespace LPhilippo\CastableFormRequest\Casting;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Caster
{
    use HasAttributes;

    /**
     * Constructor.
     *
     * @param array $casts
     */
    public function __construct(array $casts)
    {
        $this->casts = $this->normaliseCastings(
            $casts
        );
    }

    /**
     * This method is unnecessary for the implementation, but required through
     * the trait of Eloquent that we're applying.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Casts the given data based on the defined casting rules.
     *
     * @param array $data
     *
     * @return array
     */
    public function cast(array $data)
    {
        foreach ($data as $key => $value) {
            $castTo = Arr::get($this->casts, $key);

            if ($castTo === null) {
                if (Arr::first(array_keys($this->casts)) === '*') {
                    $castTo = $this->casts['*'];
                } else {
                    continue;
                }
            }

            if ($castTo === 'array') {
                $data[$key] = $this->castArray(
                    $key,
                    Arr::wrap($value)
                );
            } else {
                $data[$key] = $this->castAttribute($key, $value);
            }
        }

        return $data;
    }

    /**
     * Normalise the casts. Make sure that any child casting, has a parent with `array` casting.
     *
     * @param array $casts
     *
     * @return array
     */
    protected function normaliseCastings($casts)
    {
        $castKeys = array_keys($casts);

        foreach ($castKeys as $castKey) {
            while ($lastDotOccurence = mb_strrpos($castKey, '.')) {
                $castKey = mb_substr($castKey, 0, $lastDotOccurence);
                if (!Arr::has($castKeys, $castKey)) {
                    $casts = array_merge([
                        $castKey => 'array',
                    ], $casts);
                }
            }
        }

        return $casts;
    }

    /**
     * Cast the array, if casting rules are defined.
     *
     * @param string $index
     * @param array $data
     *
     * @return array
     */
    protected function castArray(string $index, array $data)
    {
        $casts = [];

        // Look for all related casting keys for the array.
        foreach ($this->casts as $key => $value) {
            foreach ([$index, '*'] as $prefix) {
                if (mb_strpos($key, $prefix . '.') === 0) {
                    $casts[mb_substr($key, mb_strlen($prefix . '.'))] = $value;

                    break;
                }
            }
        }

        if (count($casts) === 0) {
            return $data;
        }

        if (Arr::isAssoc($data)) {
            return (new self($casts))->cast($data);
        }

        $castKeys = [];
        $castData = [];

        // We have to prepend textual keys, to keep allowing use of `hasAttributes`
        foreach ($data as $key => $value) {
            $paramKey = sprintf('param%d', $key);
            $castData[$paramKey] = $value;

            foreach ($casts as $castKey => $castValue) {
                $castKeys[Str::replaceFirst('*', $paramKey, $castKey)] = $castValue;
            }
        }

        return array_values(
            (new self(
                $castKeys
            ))->cast(
                $castData
            )
        );
    }
}
