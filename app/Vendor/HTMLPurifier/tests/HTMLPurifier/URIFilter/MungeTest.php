<?php

class HTMLPurifier_URIFilter_MungeTest extends HTMLPurifier_URIFilterHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->filter = new HTMLPurifier_URIFilter_Munge();
    }

    protected function setMunge($uri = 'http://www.google.com/url?q=%s')
    {
        $this->config->set('URI.Munge', $uri);
    }

    protected function setSecureMunge($key = 'secret')
    {
        if (!function_exists('hash_hmac')) return false;
        $this->setMunge('/redirect.php?url=%s&checksum=%t');
        $this->config->set('URI.MungeSecretKey', $key);
        return true;
    }

    public function testMunge()
    {
        $this->setMunge();
        $this->assertFiltering(
            'http://www.example.com/',
            'http://www.google.com/url?q=http%3A%2F%2Fwww.example.com%2F'
        );
    }

    public function testMungeReplaceTagName()
    {
        $this->setMunge('/r?tagname=%n&url=%s');
        $token = new HTMLPurifier_Token_Start('a');
        $this->context->register('CurrentToken', $token);
        $this->assertFiltering('http://google.com', '/r?tagname=a&url=http%3A%2F%2Fgoogle.com');
    }

    public function testMungeReplaceAttribute()
    {
        $this->setMunge('/r?attr=%m&url=%s');
        $attr = 'href';
        $this->context->register('CurrentAttr', $attr);
        $this->assertFiltering('http://google.com', '/r?attr=href&url=http%3A%2F%2Fgoogle.com');
    }

    public function testMungeReplaceResource()
    {
        $this->setMunge('/r?embeds=%r&url=%s');
        $embeds = false;
        $this->context->register('EmbeddedURI', $embeds);
        $this->assertFiltering('http://google.com', '/r?embeds=&url=http%3A%2F%2Fgoogle.com');
    }

    public function testMungeReplaceCSSProperty()
    {
        $this->setMunge('/r?property=%p&url=%s');
        $property = 'background';
        $this->context->register('CurrentCSSProperty', $property);
        $this->assertFiltering('http://google.com', '/r?property=background&url=http%3A%2F%2Fgoogle.com');
    }

    public function testIgnoreEmbedded()
    {
        $this->setMunge();
        $embeds = true;
        $this->context->register('EmbeddedURI', $embeds);
        $this->assertFiltering('http://example.com');
    }

    public function testProcessEmbedded()
    {
        $this->setMunge();
        $this->config->set('URI.MungeResources', true);
        $embeds = true;
        $this->context->register('EmbeddedURI', $embeds);
        $this->assertFiltering('http://www.example.com/', 'http://www.google.com/url?q=http%3A%2F%2Fwww.example.com%2F');
    }

    public function testPreserveRelative()
    {
        $this->setMunge();
        $this->assertFiltering('index.html');
    }

    public function testMungeIgnoreUnknownSchemes()
    {
        $this->setMunge();
        $this->assertFiltering('javascript:foobar();', true);
    }

    public function testSecureMungePreserve()
    {
        if (!$this->setSecureMunge()) return;
        $this->assertFiltering('/local');
    }

    public function testSecureMungePreserveEmbedded()
    {
        if (!$this->setSecureMunge()) return;
        $embedded = true;
        $this->context->register('EmbeddedURI', $embedded);
        $this->assertFiltering('http://google.com');
    }

    public function testSecureMungeStandard()
    {
        if (!$this->setSecureMunge()) return;
        $this->assertFiltering('http://google.com', '/redirect.php?url=http%3A%2F%2Fgoogle.com&checksum=46267a796aca0ea5839f24c4c97ad2648373a4eca31b1c0d1fa7c7ff26798f79');
    }

    public function testSecureMungeIgnoreUnknownSchemes()
    {
        // This should be integration tested as well to be false
        if (!$this->setSecureMunge()) return;
        $this->assertFiltering('javascript:', true);
    }

    public function testSecureMungeIgnoreUnbrowsableSchemes()
    {
        if (!$this->setSecureMunge()) return;
        $this->assertFiltering('news:', true);
    }

    public function testSecureMungeToDirectory()
    {
        if (!$this->setSecureMunge()) return;
        $this->setMunge('/links/%s/%t');
        $this->assertFiltering('http://google.com', '/links/http%3A%2F%2Fgoogle.com/46267a796aca0ea5839f24c4c97ad2648373a4eca31b1c0d1fa7c7ff26798f79');
    }

    public function testMungeIgnoreSameDomain()
    {
        $this->setMunge('http://example.com/%s');
        $this->assertFiltering('http://example.com/foobar');
    }

    public function testMungeIgnoreSameDomainInsecureToSecure()
    {
        $this->setMunge('http://example.com/%s');
        $this->assertFiltering('https://example.com/foobar');
    }

    public function testMungeIgnoreSameDomainSecureToSecure()
    {
        $this->config->set('URI.Base', 'https://example.com');
        $this->setMunge('http://example.com/%s');
        $this->assertFiltering('https://example.com/foobar');
    }

    public function testMungeSameDomainSecureToInsecure()
    {
        $this->config->set('URI.Base', 'https://example.com');
        $this->setMunge('/%s');
        $this->assertFiltering('http://example.com/foobar', '/http%3A%2F%2Fexample.com%2Ffoobar');
    }

    public function testMungeIgnoresSourceHost()
    {
        $this->config->set('URI.Host', 'foo.example.com');
        $this->setMunge('http://example.com/%s');
        $this->assertFiltering('http://foo.example.com/bar');
    }

}

// vim: et sw=4 sts=4
