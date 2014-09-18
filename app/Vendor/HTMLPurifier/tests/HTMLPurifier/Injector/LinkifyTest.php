<?php

class HTMLPurifier_Injector_LinkifyTest extends HTMLPurifier_InjectorHarness
{

    public function setup()
    {
        parent::setup();
        $this->config->set('AutoFormat.Linkify', true);
    }

    public function testLinkifyURLInRootNode()
    {
        $this->assertResult(
            'http://example.com',
            '<a href="http://example.com">http://example.com</a>'
        );
    }

    public function testLinkifyURLInInlineNode()
    {
        $this->assertResult(
            '<b>http://example.com</b>',
            '<b><a href="http://example.com">http://example.com</a></b>'
        );
    }

    public function testBasicUsageCase()
    {
        $this->assertResult(
            'This URL http://example.com is what you need',
            'This URL <a href="http://example.com">http://example.com</a> is what you need'
        );
    }

    public function testIgnoreURLInATag()
    {
        $this->assertResult(
            '<a>http://example.com/</a>'
        );
    }

    public function testNeeded()
    {
        $this->config->set('HTML.Allowed', 'b');
        $this->expectError('Cannot enable Linkify injector because a is not allowed');
        $this->assertResult('http://example.com/');
    }

    public function testExcludes()
    {
        $this->assertResult('<a><span>http://example.com</span></a>');
    }

}

// vim: et sw=4 sts=4
