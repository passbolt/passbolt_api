<?php
namespace UserAgentParserTest\Unit\Provider;

use UserAgentParser\Provider\Wurfl;

/**
 * @covers UserAgentParser\Provider\Wurfl
 */
class WurflTest extends AbstractProviderTestCase
{
    private function getManager()
    {
        $mock = $this->getMock('Wurfl\Manager', [], [], '', false);

        return $mock;
    }

    public function testName()
    {
        $provider = new Wurfl($this->getManager());

        $this->assertEquals('Wurfl', $provider->getName());
    }

    public function testGetHomepage()
    {
        $provider = new Wurfl($this->getManager());

        $this->assertEquals('https://github.com/mimmi20/Wurfl', $provider->getHomepage());
    }

    public function testGetPackageName()
    {
        $provider = new Wurfl($this->getManager());

        $this->assertEquals('mimmi20/wurfl', $provider->getPackageName());
    }

    public function testVersion()
    {
        $return          = new \stdClass();
        $return->version = 'for API 1.6.4, db.scientiamobile.com - 2015-12-03 14:33:12';

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getWurflInfo')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

        $this->assertInternalType('string', $provider->getVersion());
        $this->assertEquals('1.6.4', $provider->getVersion());
    }

    public function testVersionNull()
    {
        $return          = new \stdClass();
        $return->version = 'something elese';

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getWurflInfo')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

        $this->assertNull($provider->getVersion());
    }

    public function testUpdateDate()
    {
        $return              = new \stdClass();
        $return->lastUpdated = '2015-10-16 11:09:44 -0400';

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getWurflInfo')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

        $this->assertInstanceOf('DateTime', $provider->getUpdateDate());
    }

    public function testUpdateDateBlank()
    {
        $return              = new \stdClass();
        $return->lastUpdated = '';

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getWurflInfo')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

        $this->assertNull($provider->getUpdateDate());
    }

    public function testDetectionCapabilities()
    {
        $provider = new Wurfl($this->getManager());

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
                'type'     => true,
                'isMobile' => true,
                'isTouch'  => true,
            ],

            'bot' => [
                'isBot' => true,
                'name'  => false,
                'type'  => false,
            ],
        ], $provider->getDetectionCapabilities());
    }

    public function testParser()
    {
        $manager = $this->getManager();

        $provider = new Wurfl($manager);

        $this->assertSame($manager, $provider->getParser());
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundException()
    {
        $return = $this->getMock('Wurfl\CustomDevice', [], [], '', false);

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getDeviceForUserAgent')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

        $result = $provider->parse('A real user agent...');
    }

    /**
     * Bot
     */
    public function testParseBot()
    {
        $return     = $this->getMock('Wurfl\CustomDevice', [], [], '', false);
        $return->id = 'some_id';
        $return->expects($this->any())
            ->method('getVirtualCapability')
            ->with('is_robot')
            ->will($this->returnValue('true'));

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getDeviceForUserAgent')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

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
        $return     = $this->getMock('Wurfl\CustomDevice', [], [], '', false);
        $return->id = 'some_id';

        $map = [
            [
                'is_robot',
                'false',
            ],
            [
                'advertised_browser',
                'Firefox',
            ],
            [
                'advertised_browser_version',
                '3.0.1',
            ],
        ];

        $return->expects($this->any())
            ->method('getVirtualCapability')
            ->will($this->returnValueMap($map));

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getDeviceForUserAgent')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

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
     * OS only
     */
    public function testParseOperatingSystem()
    {
        $return     = $this->getMock('Wurfl\CustomDevice', [], [], '', false);
        $return->id = 'some_id';

        $map = [
            [
                'is_robot',
                'false',
            ],
            [
                'advertised_device_os',
                'Windows',
            ],
            [
                'advertised_device_os_version',
                '7.0.1',
            ],
        ];

        $return->expects($this->any())
            ->method('getVirtualCapability')
            ->will($this->returnValueMap($map));

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getDeviceForUserAgent')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

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
     * OS - default value
     */
    public function testParseOperatingSystemDefaultValue()
    {
        $return     = $this->getMock('Wurfl\CustomDevice', [], [], '', false);
        $return->id = 'some_id';

        $map = [
            [
                'is_robot',
                'false',
            ],
            [
                'advertised_device_os',
                'Unknown',
            ],
        ];

        $return->expects($this->any())
            ->method('getVirtualCapability')
            ->will($this->returnValueMap($map));

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getDeviceForUserAgent')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'operatingSystem' => [
                'name'    => null,
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
     * Device only
     */
    public function testParseDevice()
    {
        $return     = $this->getMock('Wurfl\CustomDevice', [], [], '', false);
        $return->id = 'some_id';

        $map = [
            [
                'is_robot',
                'false',
            ],
            [
                'is_full_desktop',
                'false',
            ],

            [
                'is_mobile',
                'true',
            ],
            [
                'is_touchscreen',
                'true',
            ],
            [
                'form_factor',
                'smartphone',
            ],
        ];

        $return->expects($this->any())
            ->method('getVirtualCapability')
            ->will($this->returnValueMap($map));

        $map = [
            [
                'model_name',
                'iPhone',
            ],
            [
                'brand_name',
                'Apple',
            ],
        ];
        $return->expects($this->any())
            ->method('getCapability')
            ->will($this->returnValueMap($map));

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getDeviceForUserAgent')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

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
     * Device only
     */
    public function testParseDeviceDefaultValue()
    {
        $return     = $this->getMock('Wurfl\CustomDevice', [], [], '', false);
        $return->id = 'some_id';

        $map = [
            [
                'is_robot',
                'false',
            ],
            [
                'is_full_desktop',
                'false',
            ],

            [
                'form_factor',
                'smartphone',
            ],
        ];

        $return->expects($this->any())
            ->method('getVirtualCapability')
            ->will($this->returnValueMap($map));

        $map = [
            [
                'model_name',
                'Android',
            ],
            [
                'brand_name',
                'Generic',
            ],
        ];
        $return->expects($this->any())
            ->method('getCapability')
            ->will($this->returnValueMap($map));

        $manager = $this->getManager();
        $manager->expects($this->any())
            ->method('getDeviceForUserAgent')
            ->will($this->returnValue($return));

        $provider = new Wurfl($manager);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'device' => [
                'model' => null,
                'brand' => null,
                'type'  => 'smartphone',

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
        $class  = new \ReflectionClass('UserAgentParser\Provider\Wurfl');
        $method = $class->getMethod('isRealResult');
        $method->setAccessible(true);

        $manager = $this->getManager();

        $provider = new Wurfl($manager);

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
             * osName
             */
            [
                'Unknown',
                'operatingSystem',
                'name',
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

            /*
             * deviceModel
             */
            [
                'Android',
                'device',
                'model',
                false,
            ],
            [
                'SmartTV',
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
            [
                'Windows Mobile',
                'device',
                'model',
                false,
            ],
            [
                'Firefox',
                'device',
                'model',
                false,
            ],
            [
                'unrecognized',
                'device',
                'model',
                false,
            ],
            [
                'Generic',
                'device',
                'model',
                false,
            ],
            [
                'Disguised as Macintosh',
                'device',
                'model',
                false,
            ],
            [
                'Windows RT',
                'device',
                'model',
                false,
            ],
            [
                'Tablet on Android',
                'device',
                'model',
                false,
            ],
        ];
    }
}
