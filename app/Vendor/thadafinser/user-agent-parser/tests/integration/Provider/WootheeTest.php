<?php
namespace UserAgentParserTest\Integration\Provider;

use UserAgentParser\Provider\Woothee;

/**
 * @coversNothing
 */
class WootheeTest extends AbstractProviderTestCase
{
    public function testMethodParse()
    {
        $provider = new Woothee();
        $parser   = $provider->getParser();

        /*
         * test method exists
         */
        $class = new \ReflectionClass($parser);

        $this->assertTrue($class->hasMethod('parse'), 'method parse() does not exist anymore');

        /*
         * test paramters
         */
        $method     = $class->getMethod('parse');
        $parameters = $method->getParameters();

        $this->assertEquals(1, count($parameters));
    }

    public function testParseResult()
    {
        $provider = new Woothee();
        $parser   = $provider->getParser();

        /* @var $result \UAParser\Result\Result */
        $result = $parser->parse('A real user agent...');

        $this->assertInternalType('array', $result);

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('version', $result);
        $this->assertArrayHasKey('os', $result);
        $this->assertArrayHasKey('os_version', $result);
        $this->assertArrayHasKey('vendor', $result);
        $this->assertArrayHasKey('category', $result);
    }
}
