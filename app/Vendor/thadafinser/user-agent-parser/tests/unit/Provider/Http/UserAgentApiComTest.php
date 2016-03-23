<?php
namespace UserAgentParserTest\Unit\Provider;

use GuzzleHttp\Psr7\Response;
use stdClass;
use UserAgentParser\Provider\Http\UserAgentApiCom;

/**
 * @covers UserAgentParser\Provider\Http\UserAgentApiCom
 */
class UserAgentApiComTest extends AbstractProviderTestCase
{
    public function testName()
    {
        $provider = new UserAgentApiCom($this->getClient(), 'apiKey123');

        $this->assertEquals('UserAgentApiCom', $provider->getName());
    }

    public function testGetHomepage()
    {
        $provider = new UserAgentApiCom($this->getClient(), 'apiKey123');

        $this->assertEquals('http://useragentapi.com/', $provider->getHomepage());
    }

    public function testGetPackageName()
    {
        $provider = new UserAgentApiCom($this->getClient(), 'apiKey123');

        $this->assertNull($provider->getPackageName());
    }

    public function testVersion()
    {
        $provider = new UserAgentApiCom($this->getClient(), 'apiKey123');

        $this->assertNull($provider->getVersion());
    }

    public function testDetectionCapabilities()
    {
        $provider = new UserAgentApiCom($this->getClient(), 'apiKey123');

        $this->assertEquals([

            'browser' => [
                'name'    => true,
                'version' => true,
            ],

            'renderingEngine' => [
                'name'    => true,
                'version' => true,
            ],

            'operatingSystem' => [
                'name'    => false,
                'version' => false,
            ],

            'device' => [
                'model'    => false,
                'brand'    => false,
                'type'     => true,
                'isMobile' => false,
                'isTouch'  => false,
            ],

            'bot' => [
                'isBot' => true,
                'name'  => true,
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

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('');
    }

    /**
     * 400 - key_invalid
     *
     * @expectedException \UserAgentParser\Exception\InvalidCredentialsException
     */
    public function testGetResultInvalidCredentialsException()
    {
        $rawResult              = new stdClass();
        $rawResult->error       = new stdClass();
        $rawResult->error->code = 'key_invalid';

        $responseQueue = [
            new Response(400, [], json_encode($rawResult)),
        ];

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * 400 - useragent_invalid
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestExceptionUserAgentInvalid()
    {
        $rawResult              = new stdClass();
        $rawResult->error       = new stdClass();
        $rawResult->error->code = 'useragent_invalid';

        $responseQueue = [
            new Response(400, [], json_encode($rawResult)),
        ];

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
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

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

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

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * No result found
     *
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testGetResultNoResultFoundException()
    {
        $rawResult              = new stdClass();
        $rawResult->error       = new stdClass();
        $rawResult->error->code = 'useragent_not_found';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * Missing data
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestExceptionNoData()
    {
        $rawResult = new stdClass();

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * Bot
     */
    public function testParseBot()
    {
        $data                = new stdClass();
        $data->platform_type = 'Bot';
        $data->platform_name = 'Googlebot';

        $rawResult       = new stdClass();
        $rawResult->data = $data;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'bot' => [
                'isBot' => true,
                'name'  => 'Googlebot',
                'type'  => null,
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Browser only
     */
    public function testParseBrowser()
    {
        $data                  = new stdClass();
        $data->browser_name    = 'Firefox';
        $data->browser_version = '3.0.1';

        $rawResult       = new stdClass();
        $rawResult->data = $data;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'browser' => [
                'name'    => 'Firefox',
                'version' => [
                    'major' => 3,
                    'minor' => 0,
                    'patch' => 1,

                    'alias' => null,

                    'complete' => '3.0.1',
                ],
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Rendering engine only
     */
    public function testParseRenderingEngine()
    {
        $data                 = new stdClass();
        $data->engine_name    = 'Webkit';
        $data->engine_version = '3.2.1';

        $rawResult       = new stdClass();
        $rawResult->data = $data;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'renderingEngine' => [
                'name'    => 'Webkit',
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
     * Device only
     */
    public function testParseDevice()
    {
        $data                = new stdClass();
        $data->platform_type = 'mobile';

        $rawResult       = new stdClass();
        $rawResult->data = $data;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentApiCom($this->getClient($responseQueue), 'apiKey123');

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
