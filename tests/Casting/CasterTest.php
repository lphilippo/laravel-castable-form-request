<?php

namespace Tests\Casting;

use Carbon\Carbon;
use LPhilippo\CastableFormRequest\Casting\Caster;
use PHPUnit\Framework\TestCase as BaseTestCase;

class CasterTest extends BaseTestCase
{
    public function testCastingOfInteger()
    {
        $caster = new Caster([
            'key' => 'integer',
        ]);

        $this->assertSame($caster->cast([
            'key' => '1',
        ]), [
            'key' => 1,
        ]);
    }

    public function testCastingOfDatetime()
    {
        $caster = new Caster([
            'key' => 'datetime',
        ]);

        $this->assertInstanceOf(Carbon::class, $caster->cast([
            'key' => '2020-01-01',
        ])['key']);
    }

    public function testCastingOnly()
    {
        $caster = new Caster([
            'key' => 'integer',
        ]);

        $this->assertSame($caster->cast([
            'key' => '1',
        ]), [
            'key' => 1,
        ]);
    }
}
