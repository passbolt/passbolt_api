<?php
namespace UserAgentParserTest\Unit\Provider;

use GuzzleHttp\Psr7\Response;
use stdClass;
use UserAgentParser\Provider\Http\UserAgentStringCom;

/**
 * @covers UserAgentParser\Provider\Http\UserAgentStringCom
 */
class UserAgentStringComTest extends AbstractProviderTestCase
{
    public function testName()
    {
        $provider = new UserAgentStringCom($this->getClient());

        $this->assertEquals('UserAgentStringCom', $provider->getName());
    }

    public function testGetHomepage()
    {
        $provider = new UserAgentStringCom($this->getClient());

        $this->assertEquals('http://www.useragentstring.com/', $provider->getHomepage());
    }

    public function testGetPackageName()
    {
        $provider = new UserAgentStringCom($this->getClient());

        $this->assertNull($provider->getPackageName());
    }

    public function testVersion()
    {
        $provider = new UserAgentStringCom($this->getClient());

        $this->assertNull($provider->getVersion());
    }

    public function testDetectionCapabilities()
    {
        $provider = new UserAgentStringCom($this->getClient());

        $this->assertEquals([

            'browser' => [
                'name'    => true,
                'version' => true,
            ],

            'renderingEngine' => [
                'name'    => false,
                'version' => false,
            ],

            'operatingSystem' => [
                'name'    => true,
                'version' => true,
            ],

            'device' => [
                'model'    => false,
                'brand'    => false,
                'type'     => false,
                'isMobile' => false,
                'isTouch'  => false,
            ],

            'bot' => [
                'isBot' => true,
                'name'  => true,
                'type'  => true,
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

        $provider = new UserAgentStringCom($this->getClient($responseQueue));

        $provider->parse('');
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

        $provider = new UserAgentStringCom($this->getClient($responseQueue));

        $provider->parse('A real user agent...');
    }

    /**
     * Missing data
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestExceptionNoData()
    {
        $rawResult = 'something';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentStringCom($this->getClient($responseQueue));

        $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testParseNoResultFoundException()
    {
        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode(new stdClass())),
        ];

        $provider = new UserAgentStringCom($this->getClient($responseQueue));

        $result = $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testParseNoResultFoundExceptionDefaultValue()
    {
        $rawResult             = new stdClass();
        $rawResult->agent_type = 'unknown';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentStringCom($this->getClient($responseQueue));

        $result = $provider->parse('A real user agent...');
    }

    /**
     * Bot
     */
    public function testParseBot()
    {
        $rawResult             = new stdClass();
        $rawResult->agent_type = 'Crawler';
        $rawResult->agent_name = 'Googlebot';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentStringCom($this->getClient($responseQueue));

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'bot' => [
                'isBot' => true,
                'name'  => 'Googlebot',
                'type'  => 'Crawler',
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Browser only
     */
    public function testParseBrowser()
    {
        $rawResult                = new stdClass();
        $rawResult->agent_type    = 'Browser';
        $rawResult->agent_name    = 'Firefox';
        $rawResult->agent_version = '3.2.1';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentStringCom($this->getClient($responseQueue));

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
     * OS only
     */
    public function testParseOperatingSystem()
    {
        $rawResult                   = new stdClass();
        $rawResult->agent_type       = 'Browser';
        $rawResult->os_name          = 'BlackBerryOS';
        $rawResult->os_versionNumber = '6.0.0';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentStringCom($this->getClient($responseQueue));

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'operatingSystem' => [
                'name'    => 'BlackBerryOS',
                'version' => [
                    'major' => 6,
                    'minor' => 0,
                    'patch' => 0,

                    'alias' => null,

                    'complete' => '6.0.0',
                ],
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * OS only
     */
    public function testVersionUnderscore()
    {
        $rawResult                   = new stdClass();
        $rawResult->agent_type       = 'Browser';
        $rawResult->agent_version    = '6_0_2';
        $rawResult->os_versionNumber = '6_5_4';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new UserAgentStringCom($this->getClient($responseQueue));

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'browser' => [
                'name'    => null,
                'version' => [
                    'major' => 6,
                    'minor' => 0,
                    'patch' => 2,

                    'alias' => null,

                    'complete' => '6.0.2',
                ],
            ],

            'operatingSystem' => [
                'name'    => null,
                'version' => [
                    'major' => 6,
                    'minor' => 5,
                    'patch' => 4,

                    'alias' => null,

                    'complete' => '6.5.4',
                ],
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * @dataProvider isRealResult
     */
    public function testRealResult($value, $group, $part, $expectedResult)
    {
        $class  = new \ReflectionClass('UserAgentParser\Provider\Http\UserAgentStringCom');
        $method = $class->getMethod('isRealResult');
        $method->setAccessible(true);

        $provider = new UserAgentStringCom($this->getClient([]), 'apiUser', 'apiKey123');

        $actualResult = $method->invokeArgs($provider, [
            $value,
            $group,
            $part,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function isRealResult()
    {
        return [
            [
                'unknown',
                null,
                null,
                false,
            ],
        ];
    }
}
