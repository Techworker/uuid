<?php

class UuidTest extends PHPUnit_Framework_TestCase
{
    function testConstructor()
    {
        $uuid = new \Techworker\Uuid();
        $this->assertEquals("00000000-0000-0000-0000-000000000000", $uuid);

        $bl = new \Techworker\Uuid\ByteList(1);
        $bl->add(255, 0);
        $uuid = new \Techworker\Uuid($bl);
        $this->assertEquals("ff000000-0000-0000-0000-000000000000", $uuid->toString(new \Techworker\Uuid\FormatProvider\Microsoft\D()));

        $bl = new \Techworker\Uuid\ByteList(2);
        $bl->add(255, 0);
        $bl->add(255, 1);
        $uuid = new \Techworker\Uuid($bl);
        $this->assertEquals("ffff0000-0000-0000-0000-000000000000", $uuid->toString(new \Techworker\Uuid\FormatProvider\Microsoft\D()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testSetVariant()
    {
        // set first bit to 1 for better checking
        $uuid = new \Techworker\Uuid();
        $uuid->setAt(8, 255);

        $uuid->setVariant(\Techworker\Uuid::VARIANT_NCS);
        $this->assertEquals("0", substr(sprintf("%08d", decbin($uuid->getAt(8))), 0, 1));

        $uuid->setVariant(\Techworker\Uuid::VARIANT_RFC_4122);
        $this->assertEquals("10", substr(decbin($uuid->getAt(8)), 0, 2));

        $uuid->setVariant(\Techworker\Uuid::VARIANT_MICROSOFT);
        $this->assertEquals("110", substr(decbin($uuid->getAt(8)), 0, 3));

        $uuid->setVariant(\Techworker\Uuid::VARIANT_FUTURE);
        $this->assertEquals("111", substr(decbin($uuid->getAt(8)), 0, 3));

        $uuid = new \Techworker\Uuid();
        $uuid->setVariant("IDK");

    }

    function testSetVersion()
    {
        // set first bit to 1 for better checking
        $uuid = new \Techworker\Uuid();
        $uuid->setVersion(1);
        $this->assertEquals(1, $uuid->getAt(6) >> 4);
        $uuid->setVersion(2);
        $this->assertEquals(2, $uuid->getAt(6) >> 4);
        $uuid->setVersion(3);
        $this->assertEquals(3, $uuid->getAt(6) >> 4);
        $uuid->setVersion(4);
        $this->assertEquals(4, $uuid->getAt(6) >> 4);
        $uuid->setVersion(5);
        $this->assertEquals(5, $uuid->getAt(6) >> 4);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testFactory()
    {
        $uid = "69ec5f70-9a4f-11e4-bd06-0800200c9a66";
        $uidArr = [105, 236, 95, 112, 154, 79, 17, 228, 189, 6, 8, 0, 32, 12, 154, 102];

        // from string
        $uuid = \Techworker\Uuid::factory($uid);
        $this->assertEquals($uid, $uuid);

        // from string
        $uuid = \Techworker\Uuid::factory($uidArr);
        $this->assertEquals($uid, $uuid);

        $uuid->rewind();
        // from string
        $uuidClone = \Techworker\Uuid::factory($uuid);
        $this->assertTrue($uuid->equals($uuidClone));

        $uuid = \Techworker\Uuid::factory(new \Techworker\Uuid\ByteProvider\Nil());
        $this->assertEquals("00000000-0000-0000-0000-000000000000", $uuid);

        $uuid = \Techworker\Uuid::factory(null);
        $this->assertEquals("00000000-0000-0000-0000-000000000000", $uuid);

        $uuid = \Techworker\Uuid::factory(true);

    }


    /**
     * @expectedException \InvalidArgumentException
     */
    function testFromStringExNull()
    {
        $uuid = \Techworker\Uuid::fromString(null);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testFromStringExShort()
    {
        $uuid = \Techworker\Uuid::fromString("ABC");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testFromByteListEx()
    {
        $uuid = \Techworker\Uuid::fromByteList(new \Techworker\Uuid\ByteList());
    }

    function testV()
    {
        $v1 = \Techworker\Uuid::v1("00:00:00:00:00:00");
        $this->assertTrue($v1-> isValidUuid());
        $this->assertEquals(1, $v1->getAt(6) >> 4);
        $v2 = \Techworker\Uuid::v2("00:00:00:00:00:00", 1);
        $this->assertTrue($v2-> isValidUuid());
        $this->assertEquals(2, $v2->getAt(6) >> 4);
        $v3 = \Techworker\Uuid::v3("00:00:00:00:00:00", TW_UUID_NAMESPACE_DNS);
        $this->assertTrue($v3-> isValidUuid());
        $this->assertEquals(3, $v3->getAt(6) >> 4);
        $v4 = \Techworker\Uuid::v4();
        $this->assertTrue($v4-> isValidUuid());
        $this->assertEquals(4, $v4->getAt(6) >> 4);
        $v5 = \Techworker\Uuid::v5("00:00:00:00:00:00", TW_UUID_NAMESPACE_DNS);
        $this->assertTrue($v5-> isValidUuid());
        $this->assertEquals(5, $v5->getAt(6) >> 4);
    }


    function testEquals()
    {
        $uuid = new \Techworker\Uuid();
        $this->assertTrue($uuid->equals(new \Techworker\Uuid()));
        $this->assertTrue($uuid->equals("00000000-0000-0000-0000-000000000000"));

    }
}
