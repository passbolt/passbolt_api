<?php

class HTMLPurifier_URIFilter_HostBlacklistTest extends HTMLPurifier_URIFilterHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->filter = new HTMLPurifier_URIFilter_HostBlacklist();
    }

    public function testRejectBlacklistedHost()
    {
        $this->config->set('URI.HostBlacklist', 'example.com');
        $this->assertFiltering('http://example.com', false);
    }

    public function testRejectBlacklistedHostThoughNotTrue()
    {
        // maybe this behavior should change
        $this->config->set('URI.HostBlacklist', 'example.com');
        $this->assertFiltering('http://example.comcast.com', false);
    }

    public function testPreserveNonBlacklistedHost()
    {
        $this->config->set('URI.HostBlacklist', 'example.com');
        $this->assertFiltering('http://google.com');
    }

}

// vim: et sw=4 sts=4
