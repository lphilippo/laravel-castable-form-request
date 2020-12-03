<?php

namespace LPhilippo\CastableFormRequest\Casting;

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
        $originalRules = $this->rules();

        // Let's remove the array rules, since they disable strict limiting of keys.
        $rulesWithoutArray = array_filter($originalRules, function ($rule) {
            if (is_array($rule)) {
                return !in_array('array', $rule);
            }

            return array_search('array', explode('|', $rule)) === false;
        });

        $this->validator->setRules($rulesWithoutArray);

        $this->sanitised = (new Caster($this->casts(), []))
            ->setDateFormat($this->dateFormat)
            ->cast($this->validated());

        $this->passedSanitisation();

        $this->validator->setRules($originalRules);

        return $this->sanitised;
    }

    /**
     * Handle custom casting on a request level, for example for nested values.
     */
    protected function passedSanitisation()
    {
    }
}
