<?php

class HTMLPurifier_Strategy_CoreTest extends HTMLPurifier_StrategyHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_Core();
    }

    public function testBlankInput()
    {
        $this->assertResult('');
    }

    public function testMakeWellFormed()
    {
        $this->assertResult(
            '<b>Make well formed.',
            '<b>Make well formed.</b>'
        );
    }

    public function testFixNesting()
    {
        $this->assertResult(
            '<b><div>Fix nesting.</div></b>',
            '<b></b><div><b>Fix nesting.</b></div><b></b>'
        );
    }

    public function testRemoveForeignElements()
    {
        $this->assertResult(
            '<asdf>Foreign element removal.</asdf>',
            'Foreign element removal.'
        );
    }

    public function testFirstThree()
    {
        $this->assertResult(
            '<foo><b><div>All three.</div></b>',
            '<b></b><div><b>All three.</b></div><b></b>'
        );
    }

}

// vim: et sw=4 sts=4
