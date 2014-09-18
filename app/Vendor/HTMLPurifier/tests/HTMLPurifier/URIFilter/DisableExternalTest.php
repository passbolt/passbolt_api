<?php

class HTMLPurifier_URIFilter_DisableExternalTest extends HTMLPurifier_URIFilterHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->filter = new HTMLPurifier_URIFilter_DisableExternal();
    }

    public function testRemoveExternal()
    {
        $this->assertFiltering(
            'http://example.com', false
        );
    }

    public function testPreserveInternal()
    {
        $this->assertFiltering(
            '/foo/bar'
        );
    }

    public function testPreserveOurHost()
    {
        $this->config->set('URI.Host', 'example.com');
        $this->assertFiltering(
            'http://example.com'
        );
    }

    public function testPreserveOurSubdomain()
    {
        $this->config->set('URI.Host', 'example.com');
        $this->assertFiltering(
            'http://www.example.com'
        );
    }

    public function testRemoveSuperdomain()
    {
        $this->config->set('URI.Host', 'www.example.com');
        $this->assertFiltering(
            'http://example.com', false
        );
    }

    public function testBaseAsHost()
    {
        $this->config->set('URI.Base', 'http://www.example.com/foo/bar');
        $this->assertFiltering(
            'http://www.example.com/baz'
        );
    }

}

// vim: et sw=4 sts=4
