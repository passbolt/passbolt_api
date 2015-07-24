<?php

class HTMLPurifier_Strategy_MakeWellFormed_EndRewindInjectorTest extends HTMLPurifier_StrategyHarness
{
    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_MakeWellFormed();
        $this->config->set('AutoFormat.Custom', array(
            new HTMLPurifier_Strategy_MakeWellFormed_EndRewindInjector()
        ));
    }
    public function testBasic()
    {
        $this->assertResult('');
    }
    public function testFunction()
    {
        $this->assertResult('<span>asdf</span>','');
    }
    public function testFailedFunction()
    {
        $this->assertResult('<span>asd<b>asdf</b>asdf</span>','<span><b></b></span>');
    }
    public function testPadded()
    {
        $this->assertResult('<b></b><span>asdf</span><b></b>','<b></b><b></b>');
    }
    public function testDoubled()
    {
        $this->config->set('AutoFormat.Custom', array(
            new HTMLPurifier_Strategy_MakeWellFormed_EndRewindInjector(),
            new HTMLPurifier_Strategy_MakeWellFormed_EndRewindInjector(),
        ));
        $this->assertResult('<b></b><span>asdf</span>', '<b></b>');
    }
}

// vim: et sw=4 sts=4
