<?php

class MtRandTest extends PHPUnit_Framework_TestCase
{
    function testMtRandProvider()
    {
        $nil = new \Techworker\Uuid\ByteProvider\Random\MtRand();
        $bl = $nil->provide();
        $this->assertCount(16, $bl);
    }
}
