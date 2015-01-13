<?php

class TimeAndUidBasedTest extends PHPUnit_Framework_TestCase
{
    function testTimeAndUidBasedProvider()
    {
        $provider = new \Techworker\Uuid\ByteProvider\TimeAndUidBased(33);
        $bl = $provider->provide();
        $this->assertEquals(33, $bl->getAt(0));
        $this->assertCount(10, $bl);
    }
}
