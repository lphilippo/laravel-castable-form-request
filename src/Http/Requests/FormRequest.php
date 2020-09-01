<?php

namespace LPhilippo\CastableFormRequest\Http\Requests;

use LPhilippo\CastableFormRequest\Casting\CastsWhenValidatedTrait;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    use CastsWhenValidatedTrait;
}
