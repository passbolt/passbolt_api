<?php

class HTMLPurifier_Strategy_MakeWellFormed_EndInsertInjectorTest extends HTMLPurifier_StrategyHarness
{
    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_MakeWellFormed();
        $this->config->set('AutoFormat.Custom', array(
            new HTMLPurifier_Strategy_MakeWellFormed_EndInsertInjector()
        ));
    }
    public function testEmpty()
    {
        $this->assertResult('');
    }
    public function testNormal()
    {
        $this->assertResult('<i>Foo</i>', '<i>Foo<b>Comment</b></i>');
    }
    public function testEndOfDocumentProcessing()
    {
        $this->assertResult('<i>Foo', '<i>Foo<b>Comment</b></i>');
    }
    public function testDoubleEndOfDocumentProcessing()
    {
        $this->assertResult('<i><i>Foo', '<i><i>Foo<b>Comment</b></i><b>Comment</b></i>');
    }
    public function testEndOfNodeProcessing()
    {
        $this->assertResult('<div><i>Foo</div>asdf', '<div><i>Foo<b>Comment</b></i></div><i>asdf<b>Comment</b></i>');
    }
    public function testEmptyToStartEndProcessing()
    {
        $this->assertResult('<i />', '<i><b>Comment</b></i>');
    }
    public function testSpuriousEndTag()
    {
        $this->assertResult('</i>', '');
    }
    public function testLessButStillSpuriousEndTag()
    {
        $this->assertResult('<div></i></div>', '<div></div>');
    }
}

// vim: et sw=4 sts=4
