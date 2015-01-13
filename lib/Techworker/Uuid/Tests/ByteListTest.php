<?php

class ByteListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Techworker\Uuid\Exception\ByteList\MaxSizeExceededException
     */
    function testMaxSizeEx()
    {
        $bl = new \Techworker\Uuid\ByteList(1);
        $bl->add(0, 0);
        $bl->add(0, 1);
    }

    /**
     * @expectedException \Techworker\Uuid\Exception\ByteList\MaxSizeExceededException
     */
    function testMaxSizeExWithoutOffset()
    {
        $bl = new \Techworker\Uuid\ByteList(1);
        $bl->add(0, 0);
        $bl->add(0);
    }

    /**
     * @expectedException \Techworker\Uuid\Exception\ByteList\MaxSizeExceededException
     */
    function testIsset()
    {
        $bl = new \Techworker\Uuid\ByteList(2);
        $bl->add(0, 0);
        $bl->add(0, 1);
        $this->assertTrue($bl->isset(0));
        $this->assertTrue($bl->isset(1));
        $this->assertFalse($bl->isset(2));
    }


    /**
     * @expectedException \Techworker\Uuid\Exception\ByteList\OffsetNotValidException
     */
    function testAdd()
    {
        $bl = new \Techworker\Uuid\ByteList(10);
        for($i = 0; $i < 10; $i++) {
            $bl->add(0, $i);
        }

        $j = 0;
        foreach($bl as $k => $byte) {
            $this->assertEquals($j, $k);
            $this->assertEquals(0, $byte);
            $j++;
        }

        // test bad offset
        $bl->add(0, "ABC");
    }

    function testIterator()
    {
        $bl = new \Techworker\Uuid\ByteList(10);
        for($i = 0; $i < 10; $i++) {
            $bl->add(0, $i);
        }

        $j = 0;
        foreach($bl as $k => $byte) {
            $this->assertEquals($j, $k);
            $this->assertEquals(0, $byte);
            $j++;
        }

        $this->assertEquals(10, $bl->count());
    }

}
