<?php
namespace UserAgentParserTest\Integration\Provider;

use UserAgentParser\Provider\WhichBrowser;

/**
 * @coversNothing
 */
class WhichBrowserTest extends AbstractProviderTestCase
{
    public function testRealResult()
    {
        $provider = new WhichBrowser();

        $parser = $provider->getParser([
            'User-Agent' => 'A real user agent...',
        ]);

        $this->assertInstanceOf('WhichBrowser\Parser', $parser);

        /*
         * test method exists
         */
        $class = new \ReflectionClass($parser);

        $this->assertTrue($class->hasMethod('isDetected'), 'method isDetected() does not exist anymore');
        $this->assertTrue($class->hasMethod('toArray'), 'method toArray() does not exist anymore');
        $this->assertTrue($class->hasMethod('getType'), 'method getType() does not exist anymore');
        $this->assertTrue($class->hasMethod('isType'), 'method isType() does not exist anymore');

        $this->assertTrue($class->hasProperty('browser'), 'property browser does not exist anymore');
        $this->assertInstanceOf('WhichBrowser\Model\Browser', $parser->browser);

        $this->assertTrue($class->hasProperty('engine'), 'property engine does not exist anymore');
        $this->assertInstanceOf('WhichBrowser\Model\Engine', $parser->engine);

        $this->assertTrue($class->hasProperty('os'), 'property os does not exist anymore');
        $this->assertInstanceOf('WhichBrowser\Model\Os', $parser->os);

        $this->assertTrue($class->hasProperty('device'), 'property device does not exist anymore');
        $this->assertInstanceOf('WhichBrowser\Model\Device', $parser->device);
    }

    public function testClassBrowserResult()
    {
        $class = new \ReflectionClass('WhichBrowser\Model\Browser');

        $this->assertTrue($class->hasMethod('getName'), 'method getName() does not exist anymore');
        $this->assertTrue($class->hasMethod('getVersion'), 'method getVersion() does not exist anymore');

        $this->assertTrue($class->hasProperty('using'), 'property using does not exist anymore');
    }

    public function testClassBrowserUsingResult()
    {
        $class = new \ReflectionClass('WhichBrowser\Model\Using');

        $this->assertTrue($class->hasMethod('getName'), 'method getName() does not exist anymore');
        $this->assertTrue($class->hasMethod('getVersion'), 'method getVersion() does not exist anymore');
    }

    public function testClassEngineResult()
    {
        $class = new \ReflectionClass('WhichBrowser\Model\Engine');

        $this->assertTrue($class->hasMethod('getName'), 'method getName() does not exist anymore');
        $this->assertTrue($class->hasMethod('getVersion'), 'method getVersion() does not exist anymore');
    }

    public function testClassOsResult()
    {
        $class = new \ReflectionClass('WhichBrowser\Model\Os');

        $this->assertTrue($class->hasMethod('getName'), 'method getName() does not exist anymore');
        $this->assertTrue($class->hasMethod('getVersion'), 'method getVersion() does not exist anymore');
    }

    public function testClassDeviceResult()
    {
        $class = new \ReflectionClass('WhichBrowser\Model\Device');

        $this->assertTrue($class->hasMethod('getModel'), 'method getModel() does not exist anymore');
        $this->assertTrue($class->hasMethod('getManufacturer'), 'method getManufacturer() does not exist anymore');
    }
}
