<?php

namespace LPhilippo\CastableFormRequest\Casting;

use Illuminate\Support\Arr;

trait CastsWhenValidatedTrait
{
    /**
     * Holds all sanitised values.
     *
     * @var array
     */
    protected $sanitised = [];

    /**
     * The default storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

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
     * Default values that you want to set.
     *
     * @return array
     */
    public function defaults()
    {
        return [];
    }

    /**
     * Override default get to include `default` value, to retrieve the
     * unvalidated and uncasted value (or default value).
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return parent::get($key, $this->defaultValue($key, $default));
    }

    /**
     * Get the validated and casted data from the request.
     *
     * @param array|int|string|null $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function sanitised(array|int|string|null $key = null, mixed $default = null): mixed
    {
        $this->sanitised = (new Caster($this->casts(), []))
            ->setDateFormat($this->dateFormat)
            ->cast($this->validated());

        $this->passedSanitisation();

        return data_get($this->sanitised, $key, $default);
    }

    /**
     * Returns a specific default value, if defined.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    protected function defaultValue(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->defaults(), $key, $default);
    }

    /**
     * Handle custom casting on a request level, for example for nested values.
     */
    protected function passedSanitisation()
    {
    }
}
