<?php

namespace LPhilippo\CastableFormRequest\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use LPhilippo\CastableFormRequest\Casting\CastsWhenValidatedTrait;

class FormRequest extends BaseFormRequest
{
    use CastsWhenValidatedTrait;
}
