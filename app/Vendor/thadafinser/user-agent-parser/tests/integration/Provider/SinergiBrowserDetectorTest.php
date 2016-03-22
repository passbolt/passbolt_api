<?php
namespace UserAgentParserTest\Integration\Provider;

use UserAgentParser\Provider\SinergiBrowserDetector;

/**
 * @coversNothing
 */
class SinergiBrowserDetectorTest extends AbstractProviderTestCase
{
    public function testBrowserParser()
    {
        $provider = new SinergiBrowserDetector();

        $parser = $provider->getBrowserParser('something');

        /*
         * test method exists
         */
        $class = new \ReflectionClass($parser);

        $this->assertTrue($class->hasMethod('getName'), 'method getName() does not exist anymore');
        $this->assertTrue($class->hasMethod('getVersion'), 'method getVersion() does not exist anymore');
        $this->assertTrue($class->hasMethod('isRobot'), 'method isRobot() does not exist anymore');
    }

    public function testOsParser()
    {
        $provider = new SinergiBrowserDetector();

        $parser = $provider->getOperatingSystemParser('something');

        /*
         * test method exists
         */
        $class = new \ReflectionClass($parser);

        $this->assertTrue($class->hasMethod('getName'), 'method getName() does not exist anymore');
        $this->assertTrue($class->hasMethod('getVersion'), 'method getVersion() does not exist anymore');
        $this->assertTrue($class->hasMethod('isMobile'), 'method isMobile() does not exist anymore');
    }

    public function testDeviceParser()
    {
        $provider = new SinergiBrowserDetector();

        $parser = $provider->getDeviceParser('something');

        /*
         * test method exists
        */
        $class = new \ReflectionClass($parser);

        $this->assertTrue($class->hasMethod('getName'), 'method getName() does not exist anymore');
    }
}
