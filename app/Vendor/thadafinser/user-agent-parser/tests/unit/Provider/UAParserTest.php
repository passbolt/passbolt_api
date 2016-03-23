<?php
namespace UserAgentParserTest\Unit\Provider;

use UAParser\Result;
use UserAgentParser\Provider\UAParser;

/**
 * @covers UserAgentParser\Provider\UAParser
 */
class UAParserTest extends AbstractProviderTestCase
{
    /**
     *
     * @return \UAParser\Result\Client
     */
    private function getResultMock()
    {
        $ua     = new Result\UserAgent();
        $os     = new Result\OperatingSystem();
        $device = new Result\Device();

        $client         = new Result\Client('');
        $client->ua     = $ua;
        $client->os     = $os;
        $client->device = $device;

        return $client;
    }

    /**
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getParser($returnValue = null)
    {
        $parser = $this->getMock('UAParser\Parser', [], [], '', false);
        $parser->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($returnValue));

        return $parser;
    }

    public function testPackageNotLoadedException()
    {
        $file     = 'vendor/ua-parser/uap-php/composer.json';
        $tempFile = 'vendor/ua-parser/uap-php/composer.json.tmp';

        rename($file, $tempFile);

        try {
            $provider = new UAParser();
        } catch (\Exception $ex) {
            // we need to catch the exception, since we need to rename the file again!
        }

        $this->assertInstanceOf('UserAgentParser\Exception\PackageNotLoadedException', $ex);

        rename($tempFile, $file);
    }

    public function testName()
    {
        $provider = new UAParser();

        $this->assertEquals('UAParser', $provider->getName());
    }

    public function testGetHomepage()
    {
        $provider = new UAParser();

        $this->assertEquals('https://github.com/ua-parser/uap-php', $provider->getHomepage());
    }

    public function testGetPackageName()
    {
        $provider = new UAParser();

        $this->assertEquals('ua-parser/uap-php', $provider->getPackageName());
    }

    public function testVersion()
    {
        $provider = new UAParser();

        $this->assertInternalType('string', $provider->getVersion());
    }

    public function testUpdateDate()
    {
        $provider = new UAParser();

        $this->assertInstanceOf('DateTime', $provider->getUpdateDate());
    }

    public function testDetectionCapabilities()
    {
        $provider = new UAParser();

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
                'model'    => true,
                'brand'    => true,
                'type'     => false,
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

    public function testParser()
    {
        $provider = new UAParser();
        $this->assertInstanceOf('UAParser\Parser', $provider->getParser());

        $parser = $this->getParser();

        $provider = new UAParser($parser);

        $this->assertSame($parser, $provider->getParser());
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundException()
    {
        $parser = $this->getParser($this->getResultMock());

        $provider = new UAParser($parser);

        $result = $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundExceptionDefaultValue()
    {
        $result             = $this->getResultMock();
        $result->ua->family = 'Other';

        $parser = $this->getParser($result);

        $provider = new UAParser($parser);

        $result = $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundExceptionDefaultValueDeviceModel()
    {
        $result                = $this->getResultMock();
        $result->device->model = 'Smartphone';

        $parser = $this->getParser($result);

        $provider = new UAParser($parser);

        $result = $provider->parse('A real user agent...');
    }

    /**
     * Bot
     */
    public function testParseBot()
    {
        $result                 = $this->getResultMock();
        $result->device->family = 'Spider';
        $result->ua->family     = 'Googlebot';

        $parser = $this->getParser($result);

        $provider = new UAParser($parser);

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
     * Bot - default value
     */
    public function testParseBotDefaultValue()
    {
        $result                 = $this->getResultMock();
        $result->device->family = 'Spider';
        $result->ua->family     = 'Other';

        $parser = $this->getParser($result);

        $provider = new UAParser($parser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'bot' => [
                'isBot' => true,
                'name'  => null,
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
        $result             = $this->getResultMock();
        $result->ua->family = 'Firefox';
        $result->ua->major  = 3;
        $result->ua->minor  = 2;
        $result->ua->patch  = 1;

        $parser = $this->getParser($result);

        $provider = new UAParser($parser);

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
        $result             = $this->getResultMock();
        $result->os->family = 'Windows';
        $result->os->major  = 7;
        $result->os->minor  = 0;
        $result->os->patch  = 1;

        $parser = $this->getParser($result);

        $provider = new UAParser($parser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'operatingSystem' => [
                'name'    => 'Windows',
                'version' => [
                    'major' => 7,
                    'minor' => 0,
                    'patch' => 1,

                    'alias' => null,

                    'complete' => '7.0.1',
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
        $result                = $this->getResultMock();
        $result->device->model = 'iPhone';
        $result->device->brand = 'Apple';

        $parser = $this->getParser($result);

        $provider = new UAParser($parser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'device' => [
                'model' => 'iPhone',
                'brand' => 'Apple',
                'type'  => null,

                'isMobile' => null,
                'isTouch'  => null,
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Device - default value
     */
    public function testParseDeviceDefaultValue()
    {
        $result             = $this->getResultMock();
        $result->os->family = 'Windows';
        $result->os->major  = 7;

        $result->device->model = 'Feature Phone';
        $result->device->brand = 'Generic';

        $parser = $this->getParser($result);

        $provider = new UAParser($parser);

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

            'device' => [
                'model' => null,
                'brand' => null,
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
        $class  = new \ReflectionClass('UserAgentParser\Provider\UAParser');
        $method = $class->getMethod('isRealResult');
        $method->setAccessible(true);

        $provider = new UAParser($this->getParser($this->getResultMock()));

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
                'Other',
                null,
                null,
                false,
            ],

            /*
             * deviceBrand
             */
            [
                'Generic',
                'device',
                'brand',
                false,
            ],
            [
                'Generic',
                null,
                null,
                true,
            ],

            /*
             * deviceModel
             */
            [
                'Smartphone',
                'device',
                'model',
                false,
            ],
            [
                'Feature Phone',
                'device',
                'model',
                false,
            ],
            [
                'iOS-Device',
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
            [
                'Touch',
                'device',
                'model',
                false,
            ],
            [
                'Windows',
                'device',
                'model',
                false,
            ],
            [
                'Windows Phone',
                'device',
                'model',
                false,
            ],

            /*
             * botName
             */
            [
                'Other',
                'bot',
                'name',
                false,
            ],
            [
                'crawler',
                'bot',
                'name',
                false,
            ],
            [
                'robot',
                'bot',
                'name',
                false,
            ],
            [
                'crawl',
                'bot',
                'name',
                false,
            ],
            [
                'Spider',
                'bot',
                'name',
                false,
            ],
        ];
    }
}
