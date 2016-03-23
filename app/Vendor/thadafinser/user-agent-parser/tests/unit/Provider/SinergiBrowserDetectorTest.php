<?php
namespace UserAgentParserTest\Unit\Provider;

use UserAgentParser\Provider\SinergiBrowserDetector;

/**
 * @covers UserAgentParser\Provider\SinergiBrowserDetector
 */
class SinergiBrowserDetectorTest extends AbstractProviderTestCase
{
    /**
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getBrowserParser()
    {
        $parser = $this->getMock('Sinergi\BrowserDetector\Browser', [], [], '', false);

        return $parser;
    }

    /**
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getOsParser()
    {
        $parser = $this->getMock('Sinergi\BrowserDetector\Os', [], [], '', false);

        return $parser;
    }

    /**
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getDeviceParser()
    {
        $parser = $this->getMock('Sinergi\BrowserDetector\Device', [], [], '', false);

        return $parser;
    }

    public function testPackageNotLoadedException()
    {
        $file     = 'vendor/sinergi/browser-detector/composer.json';
        $tempFile = 'vendor/sinergi/browser-detector/composer.json.tmp';

        rename($file, $tempFile);

        try {
            $provider = new SinergiBrowserDetector();
        } catch (\Exception $ex) {
            // we need to catch the exception, since we need to rename the file again!
        }

        $this->assertInstanceOf('UserAgentParser\Exception\PackageNotLoadedException', $ex);

        rename($tempFile, $file);
    }

    public function testName()
    {
        $provider = new SinergiBrowserDetector();

        $this->assertEquals('SinergiBrowserDetector', $provider->getName());
    }

    public function testGetHomepage()
    {
        $provider = new SinergiBrowserDetector();

        $this->assertEquals('https://github.com/sinergi/php-browser-detector', $provider->getHomepage());
    }

    public function testGetPackageName()
    {
        $provider = new SinergiBrowserDetector();

        $this->assertEquals('sinergi/browser-detector', $provider->getPackageName());
    }

    public function testVersion()
    {
        $provider = new SinergiBrowserDetector();

        $this->assertInternalType('string', $provider->getVersion());
    }

    public function testUpdateDate()
    {
        $provider = new SinergiBrowserDetector();

        $this->assertInstanceOf('DateTime', $provider->getUpdateDate());
    }

    public function testDetectionCapabilities()
    {
        $provider = new SinergiBrowserDetector();

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
                'brand'    => false,
                'type'     => false,
                'isMobile' => true,
                'isTouch'  => false,
            ],

            'bot' => [
                'isBot' => true,
                'name'  => false,
                'type'  => false,
            ],
        ], $provider->getDetectionCapabilities());
    }

    public function testProvider()
    {
        $provider = new SinergiBrowserDetector();

        $this->assertInstanceOf('Sinergi\BrowserDetector\Browser', $provider->getBrowserParser(''));
        $this->assertInstanceOf('Sinergi\BrowserDetector\Os', $provider->getOperatingSystemParser(''));
        $this->assertInstanceOf('Sinergi\BrowserDetector\Device', $provider->getDeviceParser(''));
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundException()
    {
        $provider = new SinergiBrowserDetector();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('browserParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getBrowserParser());

        $property = $reflection->getProperty('osParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getOsParser());

        $property = $reflection->getProperty('deviceParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getDeviceParser());

        $result = $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundExceptionDefaultValue()
    {
        $browserParser = $this->getBrowserParser();
        $browserParser->expects($this->any())
            ->method('isRobot')
            ->will($this->returnValue(false));
        $browserParser->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('unknown'));

        $provider = new SinergiBrowserDetector();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('browserParser');
        $property->setAccessible(true);
        $property->setValue($provider, $browserParser);

        $property = $reflection->getProperty('osParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getOsParser());

        $property = $reflection->getProperty('deviceParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getDeviceParser());

        $result = $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundExceptionDefaultValue2()
    {
        $browserParser = $this->getBrowserParser();
        $browserParser->expects($this->any())
            ->method('isRobot')
            ->will($this->returnValue(false));
        $deviceParser = $this->getDeviceParser();
        $deviceParser->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Windows Phone'));

        $provider = new SinergiBrowserDetector();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('browserParser');
        $property->setAccessible(true);
        $property->setValue($provider, $browserParser);

        $property = $reflection->getProperty('osParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getOsParser());

        $property = $reflection->getProperty('deviceParser');
        $property->setAccessible(true);
        $property->setValue($provider, $deviceParser);

        $result = $provider->parse('A real user agent...');
    }

    /**
     * Bot
     */
    public function testParseBot()
    {
        $browserParser = $this->getBrowserParser();
        $browserParser->expects($this->any())
            ->method('isRobot')
            ->will($this->returnValue(true));

        $provider = new SinergiBrowserDetector();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('browserParser');
        $property->setAccessible(true);
        $property->setValue($provider, $browserParser);

        $property = $reflection->getProperty('osParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getOsParser());

        $property = $reflection->getProperty('deviceParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getDeviceParser());

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
        $browserParser = $this->getBrowserParser();
        $browserParser->expects($this->any())
            ->method('isRobot')
            ->will($this->returnValue(false));
        $browserParser->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Chrome'));
        $browserParser->expects($this->any())
            ->method('getVersion')
            ->will($this->returnValue('28.0.1468'));

        $provider = new SinergiBrowserDetector();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('browserParser');
        $property->setAccessible(true);
        $property->setValue($provider, $browserParser);

        $property = $reflection->getProperty('osParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getOsParser());

        $property = $reflection->getProperty('deviceParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getDeviceParser());

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'browser' => [
                'name'    => 'Chrome',
                'version' => [
                    'major' => 28,
                    'minor' => 0,
                    'patch' => 1468,

                    'alias' => null,

                    'complete' => '28.0.1468',
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
        $osParser = $this->getOsParser();
        $osParser->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Windows'));
        $osParser->expects($this->any())
            ->method('getVersion')
            ->will($this->returnValue('7.0.1'));

        $provider = new SinergiBrowserDetector();

        $reflection = new \ReflectionClass($provider);

        $property = $reflection->getProperty('browserParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getBrowserParser());

        $property = $reflection->getProperty('osParser');
        $property->setAccessible(true);
        $property->setValue($provider, $osParser);

        $property = $reflection->getProperty('deviceParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getDeviceParser());

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
        $osParser = $this->getOsParser();
        $osParser->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(\Sinergi\BrowserDetector\Browser::UNKNOWN));
        $osParser->expects($this->any())
            ->method('isMobile')
            ->will($this->returnValue(true));
        $deviceParser = $this->getDeviceParser();
        $deviceParser->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('iPad'));

        $provider = new SinergiBrowserDetector();

        $reflection = new \ReflectionClass($provider);

        $property = $reflection->getProperty('browserParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getBrowserParser());

        $property = $reflection->getProperty('osParser');
        $property->setAccessible(true);
        $property->setValue($provider, $osParser);

        $property = $reflection->getProperty('deviceParser');
        $property->setAccessible(true);
        $property->setValue($provider, $deviceParser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'device' => [
                'model' => 'iPad',
                'brand' => null,
                'type'  => null,

                'isMobile' => true,
                'isTouch'  => null,
            ],
        ];

        $this->assertProviderResult($result, $expectedResult);
    }

    /**
     * Device - name default
     */
    public function testParseDeviceDefaultValue()
    {
        $browserParser = $this->getBrowserParser();
        $browserParser->expects($this->any())
            ->method('isRobot')
            ->will($this->returnValue(false));
        $browserParser->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Chrome'));

        $deviceParser = $this->getDeviceParser();
        $deviceParser->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Windows Phone'));

        $provider = new SinergiBrowserDetector();

        $reflection = new \ReflectionClass($provider);

        $property = $reflection->getProperty('browserParser');
        $property->setAccessible(true);
        $property->setValue($provider, $browserParser);

        $property = $reflection->getProperty('osParser');
        $property->setAccessible(true);
        $property->setValue($provider, $this->getOsParser());

        $property = $reflection->getProperty('deviceParser');
        $property->setAccessible(true);
        $property->setValue($provider, $deviceParser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'browser' => [
                'name'    => 'Chrome',
                'version' => [
                    'major' => null,
                    'minor' => null,
                    'patch' => null,

                    'alias' => null,

                    'complete' => null,
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
        $class  = new \ReflectionClass('UserAgentParser\Provider\SinergiBrowserDetector');
        $method = $class->getMethod('isRealResult');
        $method->setAccessible(true);

        $provider = new SinergiBrowserDetector();

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
                'unknown',
                null,
                null,
                false,
            ],
            [
                'Not unknown',
                null,
                null,
                true,
            ],

            /*
             * deviceModel
             */
            [
                'Windows Phone',
                'device',
                'model',
                false,
            ],
        ];
    }
}
