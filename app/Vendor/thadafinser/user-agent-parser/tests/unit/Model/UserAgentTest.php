<?php
namespace UserAgentParserTest;

use PHPUnit_Framework_TestCase;
use UserAgentParser\Model\Browser;
use UserAgentParser\Model\OperatingSystem;
use UserAgentParser\Model\UserAgent;

/**
 * @covers UserAgentParser\Model\UserAgent
 */
class UserAgentTest extends PHPUnit_Framework_TestCase
{
    public function testBrowser()
    {
        $ua = new UserAgent();

        $this->assertInstanceOf('UserAgentParser\Model\Browser', $ua->getBrowser());

        $mock = $this->getMock('UserAgentParser\Model\Browser');
        $ua->setBrowser($mock);
        $this->assertSame($mock, $ua->getBrowser());
    }

    public function testRenderingEngine()
    {
        $ua = new UserAgent();

        $this->assertInstanceOf('UserAgentParser\Model\RenderingEngine', $ua->getRenderingEngine());

        $mock = $this->getMock('UserAgentParser\Model\RenderingEngine');
        $ua->setRenderingEngine($mock);
        $this->assertSame($mock, $ua->getRenderingEngine());
    }

    public function testOperatingSystem()
    {
        $ua = new UserAgent();

        $this->assertInstanceOf('UserAgentParser\Model\OperatingSystem', $ua->getOperatingSystem());

        $mock = $this->getMock('UserAgentParser\Model\OperatingSystem');
        $ua->setOperatingSystem($mock);
        $this->assertSame($mock, $ua->getOperatingSystem());
    }

    public function testDevice()
    {
        $ua = new UserAgent();

        $this->assertInstanceOf('UserAgentParser\Model\Device', $ua->getDevice());

        $mock = $this->getMock('UserAgentParser\Model\Device');
        $ua->setDevice($mock);
        $this->assertSame($mock, $ua->getDevice());
    }

    public function testBot()
    {
        $ua = new UserAgent();

        $this->assertInstanceOf('UserAgentParser\Model\Bot', $ua->getBot());

        $mock = $this->getMock('UserAgentParser\Model\Bot');
        $ua->setBot($mock);
        $this->assertSame($mock, $ua->getBot());
    }

    public function testProviderResultRaw()
    {
        $ua = new UserAgent();

        $this->assertNull($ua->getProviderResultRaw());

        $ua->setProviderResultRaw(['test']);
        $this->assertEquals(['test'], $ua->getProviderResultRaw());
    }

    public function testToArray()
    {
        $ua = new UserAgent();

        $this->assertEquals([
            'browser'          => $ua->getBrowser()->toArray(),
            'renderingEngine'  => $ua->getRenderingEngine()->toArray(),
            'operatingSystem'  => $ua->getOperatingSystem()->toArray(),
            'device'           => $ua->getDevice()->toArray(),
            'bot'              => $ua->getBot()->toArray(),
        ], $ua->toArray());

        $this->assertEquals([
            'browser'           => $ua->getBrowser()->toArray(),
            'renderingEngine'   => $ua->getRenderingEngine()->toArray(),
            'operatingSystem'   => $ua->getOperatingSystem()->toArray(),
            'device'            => $ua->getDevice()->toArray(),
            'bot'               => $ua->getBot()->toArray(),
            'providerResultRaw' => null,
        ], $ua->toArray(true));
    }
}
