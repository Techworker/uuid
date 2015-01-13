<?php

class MacAddressTest extends HexTest
{
    // dummy byte provider, same as hex..
    function testMacProvider()
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
