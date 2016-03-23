<?php
namespace UserAgentParserTest\Unit\Provider;

use DeviceDetector\DeviceDetector;
use UserAgentParser\Provider\PiwikDeviceDetector;

/**
 * @covers UserAgentParser\Provider\PiwikDeviceDetector
 */
class PiwikDeviceDetectorTest extends AbstractProviderTestCase
{
    /**
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getParser()
    {
        $parser = $this->getMock('DeviceDetector\DeviceDetector');

        return $parser;
    }

    public function testPackageNotLoadedException()
    {
        $file     = 'vendor/piwik/device-detector/composer.json';
        $tempFile = 'vendor/piwik/device-detector/composer.json.tmp';

        rename($file, $tempFile);

        try {
            $provider = new PiwikDeviceDetector();
        } catch (\Exception $ex) {
            // we need to catch the exception, since we need to rename the file again!
        }

        $this->assertInstanceOf('UserAgentParser\Exception\PackageNotLoadedException', $ex);

        rename($tempFile, $file);
    }

    public function testName()
    {
        $provider = new PiwikDeviceDetector();

        $this->assertEquals('PiwikDeviceDetector', $provider->getName());
    }

    public function testGetHomepage()
    {
        $provider = new PiwikDeviceDetector();

        $this->assertEquals('https://github.com/piwik/device-detector', $provider->getHomepage());
    }

    public function testGetPackageName()
    {
        $provider = new PiwikDeviceDetector();

        $this->assertEquals('piwik/device-detector', $provider->getPackageName());
    }

    public function testVersion()
    {
        $provider = new PiwikDeviceDetector();

        $this->assertInternalType('string', $provider->getVersion());
    }

    public function testUpdateDate()
    {
        $provider = new PiwikDeviceDetector();

        $this->assertInstanceOf('DateTime', $provider->getUpdateDate());
    }

    public function testDetectionCapabilities()
    {
        $provider = new PiwikDeviceDetector();

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
                'model'    => true,
                'brand'    => true,
                'type'     => true,
                'isMobile' => true,
                'isTouch'  => true,
            ],

            'bot' => [
                'isBot' => true,
                'name'  => true,
                'type'  => true,
            ],
        ], $provider->getDetectionCapabilities());
    }

    public function testParser()
    {
        $provider = new PiwikDeviceDetector();
        $this->assertInstanceOf('DeviceDetector\DeviceDetector', $provider->getParser());

        $parser = $this->getParser();

        $provider = new PiwikDeviceDetector($parser);

        $this->assertSame($parser, $provider->getParser());
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundException()
    {
        $parser = $this->getParser();

        $provider = new PiwikDeviceDetector($parser);

        $result = $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundExceptionDefaultValue()
    {
        $parser = $this->getParser();
        $parser->expects($this->any())
            ->method('getClient')
            ->will($this->returnValue([
            'name' => 'UNK',
        ]));

        $provider = new PiwikDeviceDetector($parser);

        $result = $provider->parse('A real user agent...');
    }

    /**
     * Bot
     */
    public function testParseBot()
    {
        $parser = $this->getParser();
        $parser->expects($this->any())
            ->method('isBot')
            ->will($this->returnValue(true));
        $parser->expects($this->any())
            ->method('getBot')
            ->will($this->returnValue([
            'name'     => 'Hatena RSS',
            'category' => 'something',
        ]));

        $provider = new PiwikDeviceDetector($parser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'bot' => [
                'isBot' => true,
                'name'  => 'Hatena RSS',
                'type'  => 'something',
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Bot - name default
     */
    public function testParseBotNameDefault()
    {
        $parser = $this->getParser();
        $parser->expects($this->any())
            ->method('isBot')
            ->will($this->returnValue(true));
        $parser->expects($this->any())
            ->method('getBot')
            ->will($this->returnValue([
            'name' => 'Bot',
        ]));

        $provider = new PiwikDeviceDetector($parser);

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
     * Bot - name default
     */
    public function testParseBotNameDefault2()
    {
        $parser = $this->getParser();
        $parser->expects($this->any())
            ->method('isBot')
            ->will($this->returnValue(true));
        $parser->expects($this->any())
            ->method('getBot')
            ->will($this->returnValue([
            'name' => 'Generic Bot',
        ]));

        $provider = new PiwikDeviceDetector($parser);

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
        $parser = $this->getParser();
        $parser->expects($this->any())
            ->method('getClient')
            ->will($this->returnValue([
            'name'    => 'Firefox',
            'version' => '3.0',
            'engine'  => 'WebKit',
        ]));
        $parser->expects($this->any())
            ->method('getOs')
            ->will($this->returnValue([]));

        $provider = new PiwikDeviceDetector($parser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'browser' => [
                'name'    => 'Firefox',
                'version' => [
                    'major' => 3,
                    'minor' => 0,
                    'patch' => null,

                    'alias' => null,

                    'complete' => '3.0',
                ],
            ],

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
        $parser = $this->getParser();
        $parser->expects($this->any())
            ->method('getClient')
            ->will($this->returnValue([
            'engine' => DeviceDetector::UNKNOWN,
        ]));
        $parser->expects($this->any())
            ->method('getOs')
            ->will($this->returnValue([
            'name'    => 'Windows',
            'version' => '7.0',
        ]));

        $provider = new PiwikDeviceDetector($parser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'operatingSystem' => [
                'name'    => 'Windows',
                'version' => [
                    'major' => 7,
                    'minor' => 0,
                    'patch' => null,

                    'alias' => null,

                    'complete' => '7.0',
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
        $parser = $this->getParser();
        $parser->expects($this->any())
            ->method('getClient')
            ->will($this->returnValue([]));
        $parser->expects($this->any())
            ->method('getOs')
            ->will($this->returnValue([]));

        $parser->expects($this->any())
            ->method('getDevice')
            ->will($this->returnValue(1));

        $parser->expects($this->any())
            ->method('getModel')
            ->will($this->returnValue('iPhone'));
        $parser->expects($this->any())
            ->method('getBrandName')
            ->will($this->returnValue('Apple'));
        $parser->expects($this->any())
            ->method('getDeviceName')
            ->will($this->returnValue('smartphone'));

        $parser->expects($this->any())
            ->method('isMobile')
            ->will($this->returnValue(true));

        $parser->expects($this->any())
            ->method('isTouchEnabled')
            ->will($this->returnValue(true));

        $provider = new PiwikDeviceDetector($parser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'device' => [
                'model' => 'iPhone',
                'brand' => 'Apple',
                'type'  => 'smartphone',

                'isMobile' => true,
                'isTouch'  => true,
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * @dataProvider isRealResult
     */
    public function testRealResult($value, $group, $part, $expectedResult)
    {
        $class  = new \ReflectionClass('UserAgentParser\Provider\PiwikDeviceDetector');
        $method = $class->getMethod('isRealResult');
        $method->setAccessible(true);

        $parser   = $this->getParser();
        $provider = new PiwikDeviceDetector($parser);

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
                DeviceDetector::UNKNOWN,
                'browser',
                'name',
                false,
            ],

            [
                'UNKNOWN',
                'browser',
                'name',
                true,
            ],

            /*
             * botName
             */
            [
                'Bot',
                'bot',
                'name',
                false,
            ],

            [
                'Bot123',
                'bot',
                'name',
                true,
            ],

            [
                'Generic bot',
                'bot',
                'name',
                false,
            ],
        ];
    }
}
