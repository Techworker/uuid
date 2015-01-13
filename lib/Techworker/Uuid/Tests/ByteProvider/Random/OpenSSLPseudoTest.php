<?php

class OpenSSLPseudoTest extends PHPUnit_Framework_TestCase
{
    function testMtRandProvider()
    {
        $nil = new \Techworker\Uuid\ByteProvider\Random\OpenSSLPseudo();
        $bl = $nil->provide();
        $this->assertCount(16, $bl);
    }
}
