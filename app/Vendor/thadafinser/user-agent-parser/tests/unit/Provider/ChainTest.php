<?php
namespace UserAgentParserTest\Unit\Provider;

use UserAgentParser\Provider\Chain;

/**
 * @covers UserAgentParser\Provider\Chain
 */
class ChainTest extends AbstractProviderTestCase
{
    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $provider;

    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->provider = null;
    }

    public function testProvider()
    {
        $chain = new Chain();

        $this->assertInternalType('array', $chain->getProviders());
        $this->assertCount(0, $chain->getProviders());

        $chain = new Chain([
            $this->provider,
        ]);

        $this->assertInternalType('array', $chain->getProviders());
        $this->assertCount(1, $chain->getProviders());
        $this->assertSame([
            $this->provider,
        ], $chain->getProviders());
    }

    public function testName()
    {
        $chain = new Chain();

        $this->assertEquals('Chain', $chain->getName());
    }

    public function testGetHomepage()
    {
        $provider = new Chain();

        $this->assertNull($provider->getHomepage());
    }

    public function testVersion()
    {
        $provider = new Chain();

        $this->assertNull($provider->getVersion());
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testParseNoProviderNoResultFoundException()
    {
        $chain = new Chain();

        $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';

        $chain->parse($userAgent);
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testParseWithProviderNoResultFoundException()
    {
        $provider = $this->provider;
        $provider->expects($this->any())
            ->method('parse')
            ->will($this->throwException(new \UserAgentParser\Exception\NoResultFoundException()));

        $chain = new Chain([
            $provider,
        ]);

        $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';

        $chain->parse($userAgent);
    }

    public function testParseWithProviderAndValidResult()
    {
        $resultMock = $this->getMock('UserAgentParser\Model\UserAgent');

        $provider = $this->provider;
        $provider->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($resultMock));

        $chain = new Chain([
            $provider,
        ]);

        $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';

        $this->assertSame($resultMock, $chain->parse($userAgent));
    }
}
