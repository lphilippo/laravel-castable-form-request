<?php

namespace LPhilippo\CastableFormRequest\Http\Requests;

use LPhilippo\CastableFormRequest\Casting\CastsWhenValidatedTrait;
use LPhilippo\CastableFormRequest\Http\Requests\Lumen\AbstractFormRequest;

class LumenFormRequest extends AbstractFormRequest
{
    use CastsWhenValidatedTrait;
}
