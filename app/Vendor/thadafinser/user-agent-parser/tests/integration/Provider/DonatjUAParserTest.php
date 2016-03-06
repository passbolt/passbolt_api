<?php
namespace UserAgentParserTest\Integration\Provider;

use UserAgentParser\Provider\DonatjUAParser;

/**
 * @coversNothing
 */
class DonatjUAParserTest extends AbstractProviderTestCase
{
    public function testFunctionReturnsArray()
    {
        $provider = new DonatjUAParser();

        $result = \parse_user_agent('A real user agent...');

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('platform', $result);
        $this->assertArrayHasKey('browser', $result);
        $this->assertArrayHasKey('version', $result);
    }
}
