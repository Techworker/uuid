<?php

class NilTest extends PHPUnit_Framework_TestCase
{
    function testNilProvider()
    {
        $nil = new \Techworker\Uuid\ByteProvider\Nil();
        $bl = $nil->provide();

        foreach($bl as $k => $v) {
            $this->assertEquals(0, $v);
        }

        $this->assertCount(16, $bl);
    }
}
