<?php

namespace Tests\Casting;

use PHPUnit\Framework\TestCase as BaseTestCase;

class CastsWhenValidatedTraitTest extends BaseTestCase
{
    public function testDefault()
    {
        $this->assertSame(true, true);
    }
}
