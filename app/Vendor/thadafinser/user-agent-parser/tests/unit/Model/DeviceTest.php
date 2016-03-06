<?php
namespace UserAgentParserTest;

use PHPUnit_Framework_TestCase;
use UserAgentParser\Model\Device;

/**
 * @covers UserAgentParser\Model\Device
 */
class DeviceTest extends PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        $device = new Device();

        $this->assertNull($device->getModel());

        $name = 'OnePlus';
        $device->setModel($name);
        $this->assertEquals($name, $device->getModel());
    }

    public function testBrand()
    {
        $device = new Device();

        $this->assertNull($device->getBrand());

        $name = 'Apple';
        $device->setBrand($name);
        $this->assertEquals($name, $device->getBrand());
    }

    public function testType()
    {
        $device = new Device();

        $this->assertNull($device->getType());

        $name = 'mobilephone';
        $device->setType($name);
        $this->assertEquals($name, $device->getType());
    }

    public function testIsMobile()
    {
        $device = new Device();

        $this->assertNull($device->getIsMobile());

        $device->setIsMobile(true);
        $this->assertTrue($device->getIsMobile());
    }

    public function testIsTouch()
    {
        $device = new Device();

        $this->assertNull($device->getIsTouch());

        $device->setIsTouch(true);
        $this->assertTrue($device->getIsTouch());
    }

    public function testToArray()
    {
        $device = new Device();

        $this->assertEquals([
            'model'    => null,
            'brand'    => null,
            'type'     => null,
            'isMobile' => null,
            'isTouch'  => null,
        ], $device->toArray());

        $device->setModel('iPad');
        $device->setBrand('Apple');
        $device->setType('tablet');
        $device->setIsMobile(false);
        $device->setIsTouch(true);

        $this->assertEquals([
            'model'    => 'iPad',
            'brand'    => 'Apple',
            'type'     => 'tablet',
            'isMobile' => false,
            'isTouch'  => true,
        ], $device->toArray());
    }
}
