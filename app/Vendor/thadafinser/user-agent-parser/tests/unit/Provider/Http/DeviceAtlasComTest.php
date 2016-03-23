<?php
namespace UserAgentParserTest\Unit\Provider;

use GuzzleHttp\Psr7\Response;
use stdClass;
use UserAgentParser\Provider\Http\DeviceAtlasCom;

/**
 * @covers UserAgentParser\Provider\Http\DeviceAtlasCom
 */
class DeviceAtlasComTest extends AbstractProviderTestCase
{
    public function testName()
    {
        $provider = new DeviceAtlasCom($this->getClient(), 'apiKey123');

        $this->assertEquals('DeviceAtlasCom', $provider->getName());
    }

    public function testGetHomepage()
    {
        $provider = new DeviceAtlasCom($this->getClient(), 'apiKey123');

        $this->assertEquals('https://deviceatlas.com/', $provider->getHomepage());
    }

    public function testGetPackageName()
    {
        $provider = new DeviceAtlasCom($this->getClient(), 'apiKey123');

        $this->assertNull($provider->getPackageName());
    }

    public function testVersion()
    {
        $provider = new DeviceAtlasCom($this->getClient(), 'apiKey123');

        $this->assertNull($provider->getVersion());
    }

    public function testDetectionCapabilities()
    {
        $provider = new DeviceAtlasCom($this->getClient(), 'apiKey123');

        $this->assertEquals([

            'browser' => [
                'name'    => true,
                'version' => true,
            ],

            'renderingEngine' => [
                'name'    => true,
                'version' => false,
            ],

            'operatingSystem' => [
                'name'    => true,
                'version' => true,
            ],

            'device' => [
                'model'    => false,
                'brand'    => false,
                'type'     => true,
                'isMobile' => false,
                'isTouch'  => false,
            ],

            'bot' => [
                'isBot' => false,
                'name'  => false,
                'type'  => false,
            ],
        ], $provider->getDetectionCapabilities());
    }

    /**
     * Empty user agent
     *
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testGetResultNoResultFoundExceptionEmptyUserAgent()
    {
        $responseQueue = [
            new Response(200),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('');
    }

    /**
     * 403
     *
     * @expectedException \UserAgentParser\Exception\InvalidCredentialsException
     */
    public function testGetResultInvalidCredentialsException()
    {
        $responseQueue = [
            new Response(403),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...', [
            'Something' => 'bla',
        ]);
    }

    /**
     * 500
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestException()
    {
        $responseQueue = [
            new Response(500),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * No JSON returned
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestExceptionContentType()
    {
        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'text/html',
            ], 'something'),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * Missing data
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestExceptionNoData()
    {
        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json; charset=UTF-8',
            ], ''),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * no result found
     *
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundException()
    {
        $rawResult             = new stdClass();
        $rawResult->properties = new stdClass();

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json; charset=UTF-8',
            ], json_encode($rawResult)),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');
    }

    /**
     * Browser only
     */
    public function testParseBrowser()
    {
        $properties                 = new stdClass();
        $properties->browserName    = 'Firefox';
        $properties->browserVersion = '3.2.1';

        $rawResult             = new stdClass();
        $rawResult->properties = $properties;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json; charset=UTF-8',
            ], json_encode($rawResult)),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'browser' => [
                'name'    => 'Firefox',
                'version' => [
                    'major' => 3,
                    'minor' => 2,
                    'patch' => 1,

                    'alias' => null,

                    'complete' => '3.2.1',
                ],
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Engine only
     */
    public function testParseEngine()
    {
        $properties                         = new stdClass();
        $properties->browserRenderingEngine = 'WebKit';

        $rawResult             = new stdClass();
        $rawResult->properties = $properties;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json; charset=UTF-8',
            ], json_encode($rawResult)),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'renderingEngine' => [
                'name'    => 'WebKit',
                'version' => [
                    'major' => null,
                    'minor' => null,
                    'patch' => null,

                    'alias' => null,

                    'complete' => null,
                ],
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * OS only
     */
    public function testParseOperatingSystem()
    {
        $properties            = new stdClass();
        $properties->osName    = 'Windows';
        $properties->osVersion = '7';

        $rawResult             = new stdClass();
        $rawResult->properties = $properties;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json; charset=UTF-8',
            ], json_encode($rawResult)),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'operatingSystem' => [
                'name'    => 'Windows',
                'version' => [
                    'major' => 7,
                    'minor' => null,
                    'patch' => null,

                    'alias' => null,

                    'complete' => '7',
                ],
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Device only
     */
    public function testParseDevice()
    {
        $properties                      = new stdClass();
        $properties->primaryHardwareType = 'mobile';

        $rawResult             = new stdClass();
        $rawResult->properties = $properties;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json; charset=UTF-8',
            ], json_encode($rawResult)),
        ];

        $provider = new DeviceAtlasCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'device' => [
                'model' => null,
                'brand' => null,
                'type'  => 'mobile',

                'isMobile' => null,
                'isTouch'  => null,
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }
}
