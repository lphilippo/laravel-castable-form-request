<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

class ServiceProviderTest extends BaseTestCase
{
    public function testDefaultValues()
    {
        $this->assertSame(true, true);
    }
}
