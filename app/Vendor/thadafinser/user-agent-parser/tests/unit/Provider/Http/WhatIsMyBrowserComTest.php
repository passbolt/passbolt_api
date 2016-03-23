<?php
namespace UserAgentParserTest\Unit\Provider;

use GuzzleHttp\Psr7\Response;
use stdClass;
use UserAgentParser\Provider\Http\WhatIsMyBrowserCom;

/**
 * @covers UserAgentParser\Provider\Http\WhatIsMyBrowserCom
 */
class WhatIsMyBrowserComTest extends AbstractProviderTestCase
{
    public function testName()
    {
        $provider = new WhatIsMyBrowserCom($this->getClient(), 'apiKey123');

        $this->assertEquals('WhatIsMyBrowserCom', $provider->getName());
    }

    public function testGetHomepage()
    {
        $provider = new WhatIsMyBrowserCom($this->getClient(), 'apiKey123');

        $this->assertEquals('https://www.whatismybrowser.com/', $provider->getHomepage());
    }

    public function testGetPackageName()
    {
        $provider = new WhatIsMyBrowserCom($this->getClient(), 'apiKey123');

        $this->assertNull($provider->getPackageName());
    }

    public function testVersion()
    {
        $provider = new WhatIsMyBrowserCom($this->getClient(), 'apiKey123');

        $this->assertNull($provider->getVersion());
    }

    public function testDetectionCapabilities()
    {
        $provider = new WhatIsMyBrowserCom($this->getClient(), 'apiKey123');

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
                'name'    => true,
                'version' => true,
            ],

            'device' => [
                'model'    => true,
                'brand'    => true,
                'type'     => false,
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

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

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

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testParseNoResultFoundException()
    {
        $rawResult               = new stdClass();
        $rawResult->message_code = 'no_user_agent';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');
    }

    /**
     * usage_limit_exceeded
     *
     * @expectedException \UserAgentParser\Exception\LimitationExceededException
     */
    public function testGetResultLimitationExceededException()
    {
        $rawResult               = new stdClass();
        $rawResult->message_code = 'usage_limit_exceeded';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * no_api_user_key
     *
     * @expectedException \UserAgentParser\Exception\InvalidCredentialsException
     */
    public function testGetResultInvalidCredentialsExceptionNoKey()
    {
        $rawResult               = new stdClass();
        $rawResult->message_code = 'no_api_user_key';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * user_key_invalid
     *
     * @expectedException \UserAgentParser\Exception\InvalidCredentialsException
     */
    public function testGetResultInvalidCredentialsExceptionInvalidKey()
    {
        $rawResult               = new stdClass();
        $rawResult->message_code = 'user_key_invalid';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * unknown
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestExceptionUnknown()
    {
        $rawResult         = new stdClass();
        $rawResult->result = 'unknown';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * missing data
     *
     * @expectedException \UserAgentParser\Exception\RequestException
     */
    public function testGetResultRequestExceptionMissingData()
    {
        $rawResult         = new stdClass();
        $rawResult->result = 'success';

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundException()
    {
        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = new stdClass();

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundExceptionDefaultValue()
    {
        $parseResult               = new stdClass();
        $parseResult->user_agent   = 'A real user agent...';
        $parseResult->browser_name = 'Unknown browser';

        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = $parseResult;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundExceptionDefaultValue2()
    {
        $parseResult                     = new stdClass();
        $parseResult->user_agent         = 'A real user agent...';
        $parseResult->operating_platform = 'Mobile';

        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = $parseResult;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $provider->parse('A real user agent...');
    }

    /**
     * Browser only
     */
    public function testParseBrowser()
    {
        $parseResult                       = new stdClass();
        $parseResult->user_agent           = 'A real user agent...';
        $parseResult->browser_name         = 'Firefox';
        $parseResult->browser_version_full = '3.2.1';

        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = $parseResult;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

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
     * Browser only
     */
    public function testParseBrowserDefaultValue()
    {
        $parseResult                     = new stdClass();
        $parseResult->user_agent         = 'A real user agent...';
        $parseResult->browser_name       = 'Unknown browser';
        $parseResult->layout_engine_name = 'Webkit';

        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = $parseResult;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'renderingEngine' => [
                'name'    => 'Webkit',
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
     * Engine only
     */
    public function testParseEngine()
    {
        $parseResult                        = new stdClass();
        $parseResult->user_agent            = 'A real user agent...';
        $parseResult->layout_engine_name    = 'Webkit';
        $parseResult->layout_engine_version = '3.2.1';

        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = $parseResult;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

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
     * OS only
     */
    public function testParseOperatingSystem()
    {
        $parseResult                                = new stdClass();
        $parseResult->user_agent                    = 'A real user agent...';
        $parseResult->operating_system_name         = 'BlackBerryOS';
        $parseResult->operating_system_version_full = '6.0.0';

        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = $parseResult;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

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
     * Device only
     */
    public function testParseDeviceOnlyVendor()
    {
        $parseResult                                 = new stdClass();
        $parseResult->user_agent                     = 'A real user agent...';
        $parseResult->operating_platform_vendor_name = 'Dell';

        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = $parseResult;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'device' => [
                'model' => null,
                'brand' => 'Dell',
                'type'  => null,

                'isMobile' => null,
                'isTouch'  => null,
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Device only
     */
    public function testParseDevice()
    {
        $parseResult                                 = new stdClass();
        $parseResult->user_agent                     = 'A real user agent...';
        $parseResult->operating_platform             = 'Galaxy Note';
        $parseResult->operating_platform_vendor_name = 'Dell';

        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = $parseResult;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'device' => [
                'model' => 'Galaxy Note',
                'brand' => 'Dell',
                'type'  => null,

                'isMobile' => null,
                'isTouch'  => null,
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Device only
     */
    public function testParseDeviceDefaultValue()
    {
        $parseResult                                 = new stdClass();
        $parseResult->user_agent                     = 'A real user agent...';
        $parseResult->operating_platform             = 'Android Phone';
        $parseResult->operating_platform_vendor_name = 'Dell';

        $rawResult         = new stdClass();
        $rawResult->result = 'success';
        $rawResult->parse  = $parseResult;

        $responseQueue = [
            new Response(200, [
                'Content-Type' => 'application/json',
            ], json_encode($rawResult)),
        ];

        $provider = new WhatIsMyBrowserCom($this->getClient($responseQueue), 'apiKey123');

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'device' => [
                'model' => null,
                'brand' => 'Dell',
                'type'  => null,

                'isMobile' => null,
                'isTouch'  => null,
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * @dataProvider isRealResult
     */
    public function testRealResult($value, $group, $part, $expectedResult)
    {
        $class  = new \ReflectionClass('UserAgentParser\Provider\Http\WhatIsMyBrowserCom');
        $method = $class->getMethod('isRealResult');
        $method->setAccessible(true);

        $provider = new WhatIsMyBrowserCom($this->getClient([]), 'apiUser', 'apiKey123');

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
            /*
             * general
             */
            [
                'Unknown',
                null,
                null,
                true,
            ],
            /*
             * browserName
             */
            [
                'Unknown Mobile Browser',
                'browser',
                'name',
                false,
            ],
            [
                'Unknown browser',
                'browser',
                'name',
                false,
            ],
            [
                'Webkit based browser',
                'browser',
                'name',
                false,
            ],
            /*
             * deviceModel
             */
            [
                'HTC',
                'device',
                'model',
                false,
            ],
            [
                'HTC one',
                'device',
                'model',
                true,
            ],
            [
                'Mobile',
                'device',
                'model',
                false,
            ],
            [
                'Android Phone',
                'device',
                'model',
                false,
            ],
            [
                'Android Tablet',
                'device',
                'model',
                false,
            ],
            [
                'Tablet',
                'device',
                'model',
                false,
            ],
        ];
    }
}
