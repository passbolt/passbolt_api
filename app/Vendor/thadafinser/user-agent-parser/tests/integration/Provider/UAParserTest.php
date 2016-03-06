<?php
namespace UserAgentParserTest\Integration\Provider;

use UserAgentParser\Provider\UAParser;

/**
 * @coversNothing
 */
class UAParserTest extends AbstractProviderTestCase
{
    private function getParser()
    {
        return new \UAParser\Parser(include 'tests/resources/uaparser/regexes.php');
    }

    public function testMethodParse()
    {
        $provider = new UAParser($this->getParser());
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

        $this->assertEquals(2, count($parameters));

        /* @var $optionalPara \ReflectionParameter */
        $optionalPara = $parameters[1];

        $this->assertTrue($optionalPara->isOptional(), '2nd parameter of parse() is not optional anymore');
    }

    public function testParseResult()
    {
        $provider = new UAParser($this->getParser());
        $parser   = $provider->getParser();

        /* @var $result \UAParser\Result\Client */
        $result = $parser->parse('A real user agent...');

        $this->assertInstanceOf('UAParser\Result\Client', $result);

        $class = new \ReflectionClass($result);

        $this->assertTrue($class->hasProperty('ua'), 'property ua does not exist anymore');
        $this->assertInstanceOf('UAParser\Result\UserAgent', $result->ua);

        $this->assertTrue($class->hasProperty('os'), 'property os does not exist anymore');
        $this->assertInstanceOf('UAParser\Result\OperatingSystem', $result->os);

        $this->assertTrue($class->hasProperty('device'), 'property os does not exist anymore');
        $this->assertInstanceOf('UAParser\Result\Device', $result->device);
    }

    public function testClassBrowserResult()
    {
        $class = new \ReflectionClass('UAParser\Result\OperatingSystem');

        $this->assertTrue($class->hasProperty('family'), 'property family does not exist anymore');
        $this->assertTrue($class->hasProperty('major'), 'property major does not exist anymore');
        $this->assertTrue($class->hasProperty('minor'), 'property minor does not exist anymore');
        $this->assertTrue($class->hasProperty('patch'), 'property patch does not exist anymore');
    }

    public function testClassOsResult()
    {
        $class = new \ReflectionClass('UAParser\Result\UserAgent');

        $this->assertTrue($class->hasProperty('family'), 'property family does not exist anymore');
        $this->assertTrue($class->hasProperty('major'), 'property major does not exist anymore');
        $this->assertTrue($class->hasProperty('minor'), 'property minor does not exist anymore');
        $this->assertTrue($class->hasProperty('patch'), 'property patch does not exist anymore');
    }

    public function testClassDeviceResult()
    {
        $class = new \ReflectionClass('UAParser\Result\Device');

        $this->assertTrue($class->hasProperty('model'), 'property family does not exist anymore');
        $this->assertTrue($class->hasProperty('brand'), 'property major does not exist anymore');
        $this->assertTrue($class->hasProperty('family'), 'property family does not exist anymore');
    }
}
