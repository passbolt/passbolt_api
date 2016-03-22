<?php
namespace UserAgentParserTest;

use PHPUnit_Framework_TestCase;
use UserAgentParser\Model\OperatingSystem;
use UserAgentParser\Model\Version;

/**
 * @covers UserAgentParser\Model\OperatingSystem
 */
class OperatingSystemTest extends PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $os = new OperatingSystem();

        $this->assertNull($os->getName());

        $name = 'Windows';
        $os->setName($name);
        $this->assertEquals($name, $os->getName());
    }

    public function testVersion()
    {
        $os = new OperatingSystem();

        $this->assertInstanceOf('UserAgentParser\Model\Version', $os->getVersion());

        $version = new Version();
        $os->setVersion($version);
        $this->assertSame($version, $os->getVersion());
    }

    public function testToArray()
    {
        $os = new OperatingSystem();

        $this->assertEquals([
            'name'    => null,
            'version' => $os->getVersion()
                ->toArray(),
        ], $os->toArray());

        $os->setName('Linux');
        $this->assertEquals([
            'name'    => 'Linux',
            'version' => $os->getVersion()
                ->toArray(),
        ], $os->toArray());
    }
}
