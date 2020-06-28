<?php

namespace LPhilippo\CastableFormRequest\Casting;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Support\Arr;

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
        $this->casts = $casts;
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
        foreach (Arr::only($data, array_keys($this->casts)) as $key => $value) {
            $data[$key] = $this->castAttribute($key, $value);
        }

        return $data;
    }
}
