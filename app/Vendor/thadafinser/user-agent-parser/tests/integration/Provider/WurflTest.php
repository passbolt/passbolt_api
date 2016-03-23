<?php
namespace UserAgentParserTest\Integration\Provider;

use UserAgentParser\Provider\Wurfl;

/**
 * @coversNothing
 */
class WurflTest extends AbstractProviderTestCase
{
    private function getWurfl()
    {
        // config
        $wurflConfig = new \Wurfl\Configuration\InMemoryConfig();
        $wurflConfig->wurflFile('tests/resources/wurfl/wurfl.xml');
        $wurflConfig->persistence('memory');

        // Setup Caching
        $wurflConfig->cache('memory');

        // persistance
        $persistenceStorage = \Wurfl\Storage\Factory::create($wurflConfig->persistence);

        // cache
        $cacheStorage = \Wurfl\Storage\Factory::create($wurflConfig->cache);

        return new \Wurfl\Manager($wurflConfig, $persistenceStorage, $cacheStorage);
    }

    public function testMethodParse()
    {
        $provider = new Wurfl($this->getWurfl());
        $parser   = $provider->getParser();

        /*
         * test method exists
         */
        $class = new \ReflectionClass($parser);

        $this->assertTrue($class->hasMethod('getDeviceForUserAgent'), 'method getDeviceForUserAgent() does not exist anymore');

        /*
         * test paramters
         */
        $method     = $class->getMethod('getDeviceForUserAgent');
        $parameters = $method->getParameters();

        $this->assertEquals(1, count($parameters));
    }

    public function testRealResult()
    {
        $provider = new Wurfl($this->getWurfl());
        $parser   = $provider->getParser();

        /* @var $result \Wurfl\CustomDevice */
        $result = $parser->getDeviceForUserAgent('A real user agent...');

        $this->assertInstanceOf('Wurfl\CustomDevice', $result);

        /*
         * test method exists
         */
        $class = new \ReflectionClass($result);

        $this->assertTrue($class->hasMethod('getAllVirtualCapabilities'), 'method getAllVirtualCapabilities() does not exist anymore');
        $this->assertTrue($class->hasMethod('getAllCapabilities'), 'method getAllCapabilities() does not exist anymore');
        $this->assertTrue($class->hasMethod('getVirtualCapability'), 'method getVirtualCapability() does not exist anymore');
        $this->assertTrue($class->hasMethod('getCapability'), 'method isDetected() does not exist anymore');

        // there is no method to get the id and it's no normal property
        $this->assertEquals('generic', $result->id);
    }
}
