<?php

class HTMLPurifier_HTMLModule_NameTest extends HTMLPurifier_HTMLModuleHarness
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testBasicUse()
    {
        $this->config->set('Attr.EnableID', true);
        $this->assertResult(
            '<a name="foo">bar</a>'
        );
    }

    public function testCDATA()
    {
        $this->config->set('HTML.Attr.Name.UseCDATA', true);
        $this->assertResult(
            '<a name="2">Baz</a><a name="2">Bar</a>'
        );
    }

    public function testCDATAWithHeavyTidy()
    {
        $this->config->set('HTML.Attr.Name.UseCDATA', true);
        $this->config->set('HTML.TidyLevel', 'heavy');
        $this->assertResult('<a name="2">Baz</a>');
    }

}

// vim: et sw=4 sts=4
