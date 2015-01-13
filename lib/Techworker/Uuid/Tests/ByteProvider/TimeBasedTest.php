<?php

class TimeBasedTest extends PHPUnit_Framework_TestCase
{
    function testTimeBasedProvider()
    {
        $provider = new \Techworker\Uuid\ByteProvider\TimeBased();
        $bl = $provider->provide();
        $this->assertCount(10, $bl);
    }
}
