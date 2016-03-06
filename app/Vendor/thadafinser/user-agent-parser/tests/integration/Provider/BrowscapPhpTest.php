<?php
namespace UserAgentParserTest\Integration\Provider;

use BrowscapPHP\Browscap;
use BrowscapPHP\Helper\IniLoader;
use UserAgentParser\Provider\BrowscapPhp;

/**
 * @coversNothing
 */
class BrowscapPhpTest extends AbstractProviderTestCase
{
    private function getBrowscap($type)
    {
        $loader = new IniLoader();
        $loader->setLocalFile('tests/resources/browscap/' . $type . '_php_browscap.ini');

        $cache = new \WurflCache\Adapter\Memory();

        $browscap = new Browscap();
        $browscap->setCache($cache);
        $browscap->setLoader($loader);

        return $browscap;
    }

    public function testMethodParse()
    {
        $provider = new BrowscapPhp($this->getBrowscap('lite'));
        $parser   = $provider->getParser();

        /*
         * test method exists
         */
        $class = new \ReflectionClass($parser);

        $this->assertTrue($class->hasMethod('getBrowser'), 'method getBrowser() does not exist anymore');

        /*
         * test paramters
         */
        $method     = $class->getMethod('getBrowser');
        $parameters = $method->getParameters();

        $this->assertEquals(1, count($parameters));
    }

    /**
     * Regardless of lite/full, currently \Browscap\Formatter\PhpGetBrowser always return those
     */
    public function testRealResultLite()
    {
        $provider = new BrowscapPhp($this->getBrowscap('lite'));
        $parser   = $provider->getParser();

        /* @var $result \stdClass */
        $result = $parser->getBrowser('A real user agent...');

        $this->assertInstanceOf('stdClass', $result);

        $this->assertObjectHasAttribute('crawler', $result);
        $this->assertObjectHasAttribute('issyndicationreader', $result);

        $this->assertObjectHasAttribute('browser', $result);
        $this->assertObjectHasAttribute('browser_type', $result);
        $this->assertObjectHasAttribute('version', $result);

        $this->assertObjectHasAttribute('renderingengine_name', $result);
        $this->assertObjectHasAttribute('renderingengine_version', $result);

        $this->assertObjectHasAttribute('platform', $result);
        $this->assertObjectHasAttribute('platform_version', $result);

        $this->assertObjectHasAttribute('device_name', $result);
        $this->assertObjectHasAttribute('device_brand_name', $result);
        $this->assertObjectHasAttribute('device_type', $result);
        $this->assertObjectHasAttribute('ismobiledevice', $result);
        $this->assertObjectHasAttribute('device_pointing_method', $result);
    }

    public function testRealResultFull()
    {
        $provider = new BrowscapPhp($this->getBrowscap('full'));
        $parser   = $provider->getParser();

        /* @var $result \stdClass */
        $result = $parser->getBrowser('A real user agent...');

        $this->assertInstanceOf('stdClass', $result);

        $this->assertObjectHasAttribute('crawler', $result);
        $this->assertObjectHasAttribute('issyndicationreader', $result);

        $this->assertObjectHasAttribute('browser', $result);
        $this->assertObjectHasAttribute('browser_type', $result);
        $this->assertObjectHasAttribute('version', $result);

        $this->assertObjectHasAttribute('renderingengine_name', $result);
        $this->assertObjectHasAttribute('renderingengine_version', $result);

        $this->assertObjectHasAttribute('platform', $result);
        $this->assertObjectHasAttribute('platform_version', $result);

        $this->assertObjectHasAttribute('device_name', $result);
        $this->assertObjectHasAttribute('device_brand_name', $result);
        $this->assertObjectHasAttribute('device_type', $result);
        $this->assertObjectHasAttribute('ismobiledevice', $result);
        $this->assertObjectHasAttribute('device_pointing_method', $result);
    }
}
