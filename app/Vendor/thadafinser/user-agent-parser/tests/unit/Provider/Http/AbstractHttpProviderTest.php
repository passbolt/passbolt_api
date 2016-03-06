<?php
namespace UserAgentParserTest\Unit\Provider;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * @covers UserAgentParser\Provider\Http\AbstractHttpProvider
 */
class AbstractHttpProviderTest extends AbstractProviderTestCase
{
    /**
     * A general RequestException
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestException()
    {
        $responseQueue = [
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ];

        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\Http\AbstractHttpProvider', [
            $this->getClient($responseQueue),
        ]);

        $reflection = new \ReflectionClass($provider);
        $method     = $reflection->getMethod('getResponse');
        $method->setAccessible(true);

        $request = new Request('GET', 'http://example.com');

        $method->invoke($provider, $request);
    }

    /**
     * Got a response, but not 200
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestExceptionNotStatus200()
    {
        $responseQueue = [
            new Response(202),
        ];

        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\Http\AbstractHttpProvider', [
            $this->getClient($responseQueue),
        ]);

        $reflection = new \ReflectionClass($provider);
        $method     = $reflection->getMethod('getResponse');
        $method->setAccessible(true);

        $request = new Request('GET', 'http://example.com');

        $method->invoke($provider, $request);
    }

    /**
     * Valid response
     */
    public function testGetResultValid()
    {
        $responseQueue = [
            new Response(200),
        ];

        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\Http\AbstractHttpProvider', [
            $this->getClient($responseQueue),
        ]);

        $reflection = new \ReflectionClass($provider);
        $method     = $reflection->getMethod('getResponse');
        $method->setAccessible(true);

        $request = new Request('GET', 'http://example.com');

        $result = $method->invoke($provider, $request);

        $this->assertInstanceOf('GuzzleHttp\Psr7\Response', $result);
    }
}
