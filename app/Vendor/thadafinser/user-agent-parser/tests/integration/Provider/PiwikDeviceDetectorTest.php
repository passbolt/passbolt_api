<?php
namespace UserAgentParserTest\Integration\Provider;

use UserAgentParser\Provider\PiwikDeviceDetector;

/**
 * @coversNothing
 */
class PiwikDeviceDetectorTest extends AbstractProviderTestCase
{
    public function testMethods()
    {
        $provider = new PiwikDeviceDetector();
        $parser   = $provider->getParser();

        /*
         * test method exists
         */
        $class = new \ReflectionClass($parser);

        $this->assertTrue($class->hasMethod('setUserAgent'), 'method setUserAgent() does not exist anymore');
        $this->assertTrue($class->hasMethod('parse'), 'method parse() does not exist anymore');

        $this->assertTrue($class->hasMethod('isBot'), 'method isBot() does not exist anymore');
        $this->assertTrue($class->hasMethod('getBot'), 'method getBot() does not exist anymore');

        $this->assertTrue($class->hasMethod('getClient'), 'method getClient() does not exist anymore');
        $this->assertTrue($class->hasMethod('getOs'), 'method getOs() does not exist anymore');

        $this->assertTrue($class->hasMethod('getModel'), 'method getModel() does not exist anymore');
        $this->assertTrue($class->hasMethod('getBrandName'), 'method getBrandName() does not exist anymore');
        $this->assertTrue($class->hasMethod('getDeviceName'), 'method getDeviceName() does not exist anymore');
        $this->assertTrue($class->hasMethod('isMobile'), 'method isMobile() does not exist anymore');
        $this->assertTrue($class->hasMethod('isTouchEnabled'), 'method isTouchEnabled() does not exist anymore');
    }
}
