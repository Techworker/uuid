<?php

class HexTest extends PHPUnit_Framework_TestCase
{
    function testHexProvider()
    {
        $hexes = ["aa", "bb", "cc"];
        $hex = new \Techworker\Uuid\ByteProvider\String\Hex(implode(":", $hexes));
        $this->assertEquals("aa:bb:cc", implode(":", $hexes));
        $bl = $hex->provide();

        foreach($bl as $k => $v) {
            $this->assertEquals(hexdec($hexes[$k]), $v);
        }

        $this->assertCount(3, $bl);
    }
}
