<?php

namespace LPhilippo\CastableFormRequest\Casting;

trait CastsWhenValidatedTrait
{
    /**
     * Default values that you want to set.
     *
     * @return array
     */
    public function defaults()
    {
        return [];
    }

    /**
     * Casting rules that you want to apply.
     *
     * @return array
     */
    public function casts()
    {
        return [];
    }

    /**
     * Get the validated and casted data from the request.
     *
     * @return array
     */
    public function sanitised()
    {
        return (new Caster($this->casts(), []))->cast($this->validated());
    }
}
