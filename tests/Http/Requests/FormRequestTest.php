<?php

namespace Tests\Http\Requests;

use LPhilippo\CastableFormRequest\Http\Requests\FormRequest;
use PHPUnit\Framework\TestCase as BaseTestCase;

class FormRequestTest extends BaseTestCase
{
    public function testDefaultValues()
    {
        $formRequest = new FormRequest();
        $all = $formRequest->all();
        $this->assertSame($all, []);
    }
}
