<?php
namespace UserAgentParserTest;

use PHPUnit_Framework_TestCase;
use UserAgentParser\Model\Browser;
use UserAgentParser\Model\Version;

/**
 * @covers UserAgentParser\Model\Browser
 */
class BrowserTest extends PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $browser = new Browser();

        $this->assertNull($browser->getName());

        $name = 'Firefox';
        $browser->setName($name);
        $this->assertEquals($name, $browser->getName());
    }

    public function testVersion()
    {
        $browser = new Browser();

        $this->assertInstanceOf('UserAgentParser\Model\Version', $browser->getVersion());

        $version = new Version();
        $browser->setVersion($version);
        $this->assertSame($version, $browser->getVersion());
    }

    public function testToArray()
    {
        $browser = new Browser();

        $this->assertEquals([
            'name'    => null,
            'version' => $browser->getVersion()
                ->toArray(),
        ], $browser->toArray());

        $browser->setName('Chrome');
        $this->assertEquals([
            'name'    => 'Chrome',
            'version' => $browser->getVersion()
                ->toArray(),
        ], $browser->toArray());
    }
}
