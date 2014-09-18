<?php

class HTMLPurifier_URIParserTest extends HTMLPurifier_Harness
{

    protected function assertParsing(
        $uri, $scheme, $userinfo, $host, $port, $path, $query, $fragment, $config = null, $context = null
    ) {
        $this->prepareCommon($config, $context);
        $parser = new HTMLPurifier_URIParser();
        $result = $parser->parse($uri, $config, $context);
        $expect = new HTMLPurifier_URI($scheme, $userinfo, $host, $port, $path, $query, $fragment);
        $this->assertEqual($result, $expect);
    }

    public function testPercentNormalization()
    {
        $this->assertParsing(
            '%G',
            null, null, null, null, '%25G', null, null
        );
    }

    public function testRegular()
    {
        $this->assertParsing(
            'http://www.example.com/webhp?q=foo#result2',
            'http', null, 'www.example.com', null, '/webhp', 'q=foo', 'result2'
        );
    }

    public function testPortAndUsername()
    {
        $this->assertParsing(
            'http://user@authority.part:80/now/the/path?query#fragment',
            'http', 'user', 'authority.part', 80, '/now/the/path', 'query', 'fragment'
        );
    }

    public function testPercentEncoding()
    {
        $this->assertParsing(
            'http://en.wikipedia.org/wiki/Clich%C3%A9',
            'http', null, 'en.wikipedia.org', null, '/wiki/Clich%C3%A9', null, null
        );
    }

    public function testEmptyQuery()
    {
        $this->assertParsing(
            'http://www.example.com/?#',
            'http', null, 'www.example.com', null, '/', '', null
        );
    }

    public function testEmptyPath()
    {
        $this->assertParsing(
            'http://www.example.com',
            'http', null, 'www.example.com', null, '', null, null
        );
    }

    public function testOpaqueURI()
    {
        $this->assertParsing(
            'mailto:bob@example.com',
            'mailto', null, null, null, 'bob@example.com', null, null
        );
    }

    public function testIPv4Address()
    {
        $this->assertParsing(
            'http://192.0.34.166/',
            'http', null, '192.0.34.166', null, '/', null, null
        );
    }

    public function testFakeIPv4Address()
    {
        $this->assertParsing(
            'http://333.123.32.123/',
            'http', null, '333.123.32.123', null, '/', null, null
        );
    }

    public function testIPv6Address()
    {
        $this->assertParsing(
            'http://[2001:db8::7]/c=GB?objectClass?one',
            'http', null, '[2001:db8::7]', null, '/c=GB', 'objectClass?one', null
        );
    }

    public function testInternationalizedDomainName()
    {
        $this->assertParsing(
            "http://t\xC5\xABdali\xC5\x86.lv",
            'http', null, "t\xC5\xABdali\xC5\x86.lv", null, '', null, null
        );
    }

    public function testInvalidPort()
    {
        $this->assertParsing(
            'http://example.com:foobar',
            'http', null, 'example.com', null, '', null, null
        );
    }

    public function testPathAbsolute()
    {
        $this->assertParsing(
            'http:/this/is/path',
            'http', null, null, null, '/this/is/path', null, null
        );
    }

    public function testPathRootless()
    {
        // this should not be used but is allowed
        $this->assertParsing(
            'http:this/is/path',
            'http', null, null, null, 'this/is/path', null, null
        );
    }

    public function testPathEmpty()
    {
        $this->assertParsing(
            'http:',
            'http', null, null, null, '', null, null
        );
    }

    public function testRelativeURI()
    {
        $this->assertParsing(
            '/a/b',
            null, null, null, null, '/a/b', null, null
        );
    }

    public function testMalformedTag()
    {
        $this->assertParsing(
            'http://www.example.com/>',
            'http', null, 'www.example.com', null, '/', null, null
        );
    }

    public function testEmpty()
    {
        $this->assertParsing(
            '',
            null, null, null, null, '', null, null
        );
    }

    public function testEmbeddedColon()
    {
        $this->assertParsing(
            '{:test:}',
            null, null, null, null, '{:test:}', null, null
        );
    }

}

// vim: et sw=4 sts=4
