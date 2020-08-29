<?php

namespace LPhilippo\CastableFormRequest\Casting;

trait CastsWhenValidatedTrait
{

    /**
     * Holds all sanitised values.
     * 
     * @var array
     */
    protected array $sanitised = [];
    
    /**
     * The default storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'y-m-d H:i:s';

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
        $this->sanitised = (new Caster($this->casts(), []))
            ->setDateFormat($this->dateFormat)
            ->cast($this->validated());

        $this->passedSanitisation();

        return $this->sanitised;
    }

    /**
     * Handle custom casting on a request level, for example for nested values.
     *
     * @return void
     */
    protected function passedSanitisation()
    {
        //
    }
}
