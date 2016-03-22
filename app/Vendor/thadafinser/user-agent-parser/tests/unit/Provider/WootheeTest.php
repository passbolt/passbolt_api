<?php
namespace UserAgentParserTest\Unit\Provider;

use UserAgentParser\Provider\Woothee;

/**
 * @covers UserAgentParser\Provider\Woothee
 */
class WootheeTest extends AbstractProviderTestCase
{
    /**
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getParser(array $returnValue = [])
    {
        $parser = $this->getMock('Woothee\Classifier', [], [], '', false);
        $parser->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($returnValue));

        return $parser;
    }

    public function testPackageNotLoadedException()
    {
        $file     = 'vendor/woothee/woothee/composer.json';
        $tempFile = 'vendor/woothee/woothee/composer.json.tmp';

        rename($file, $tempFile);

        try {
            $provider = new Woothee();
        } catch (\Exception $ex) {
        }

        $this->assertInstanceOf('UserAgentParser\Exception\PackageNotLoadedException', $ex);

        rename($tempFile, $file);
    }

    public function testName()
    {
        $provider = new Woothee();

        $this->assertEquals('Woothee', $provider->getName());
    }

    public function testGetHomepage()
    {
        $provider = new Woothee();

        $this->assertEquals('https://github.com/woothee/woothee-php', $provider->getHomepage());
    }

    public function testGetPackageName()
    {
        $provider = new Woothee();

        $this->assertEquals('woothee/woothee', $provider->getPackageName());
    }

    public function testVersion()
    {
        $provider = new Woothee();

        $this->assertInternalType('string', $provider->getVersion());
    }

    public function testUpdateDate()
    {
        $provider = new Woothee();

        $this->assertInstanceOf('DateTime', $provider->getUpdateDate());
    }

    public function testDetectionCapabilities()
    {
        $provider = new Woothee();

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

    public function testParser()
    {
        $provider = new Woothee();

        $this->assertInstanceOf('Woothee\Classifier', $provider->getParser());
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundException()
    {
        $parser = $this->getParser();

        $provider = new Woothee();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('parser');
        $property->setAccessible(true);
        $property->setValue($provider, $parser);

        $result = $provider->parse('A real user agent...');
    }

    /**
     * @expectedException \UserAgentParser\Exception\NoResultFoundException
     */
    public function testNoResultFoundExceptionDefaultValue()
    {
        $parser = $this->getParser([
            'name' => 'UNKNOWN',
        ]);

        $provider = new Woothee();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('parser');
        $property->setAccessible(true);
        $property->setValue($provider, $parser);

        $result = $provider->parse('A real user agent...');
    }

    /**
     * Bot
     */
    public function testParseBot()
    {
        $parser = $this->getParser([
            'category' => \Woothee\DataSet::DATASET_CATEGORY_CRAWLER,
            'name'     => 'Googlebot',
        ]);

        $provider = new Woothee();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('parser');
        $property->setAccessible(true);
        $property->setValue($provider, $parser);

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
     * Bot
     */
    public function testParseBotDefaultValue()
    {
        $parser = $this->getParser([
            'category' => \Woothee\DataSet::DATASET_CATEGORY_CRAWLER,
            'name'     => 'misc crawler',
        ]);

        $provider = new Woothee();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('parser');
        $property->setAccessible(true);
        $property->setValue($provider, $parser);

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
        $parser = $this->getParser([
            'name'    => 'Firefox',
            'version' => '3.0.1',
        ]);

        $provider = new Woothee();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('parser');
        $property->setAccessible(true);
        $property->setValue($provider, $parser);

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
     * Device only
     */
    public function testParseDevice()
    {
        $parser = $this->getParser([
            'category' => \Woothee\DataSet::DATASET_CATEGORY_SMARTPHONE,
        ]);

        $provider = new Woothee();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('parser');
        $property->setAccessible(true);
        $property->setValue($provider, $parser);

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
     * Device only
     */
    public function testParseDeviceMobilephone()
    {
        $parser = $this->getParser([
            'category' => \Woothee\DataSet::DATASET_CATEGORY_MOBILEPHONE,
            'name'     => \Woothee\DataSet::VALUE_UNKNOWN,
        ]);

        $provider = new Woothee();

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('parser');
        $property->setAccessible(true);
        $property->setValue($provider, $parser);

        $result = $provider->parse('A real user agent...');

        $expectedResult = [
            'browser' => [
                'name'    => null,
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
                'type'  => 'mobilephone',

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
        $class  = new \ReflectionClass('UserAgentParser\Provider\Woothee');
        $method = $class->getMethod('isRealResult');
        $method->setAccessible(true);

        $provider = new Woothee();

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
                'UNKNOWN',
                null,
                null,
                false,
            ],

            /*
             * botName
             */
            [
                'misc crawler',
                'bot',
                'name',
                false,
            ],
        ];
    }
}
