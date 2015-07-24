<?php

class HTMLPurifier_ChildDef_ChameleonTest extends HTMLPurifier_ChildDefHarness
{

    protected $isInline;

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_ChildDef_Chameleon(
            'b | i',      // allowed only when in inline context
            'b | i | div' // allowed only when in block context
        );
        $this->context->register('IsInline', $this->isInline);
    }

    public function testInlineAlwaysAllowed()
    {
        $this->isInline = true;
        $this->assertResult(
            '<b>Allowed.</b>'
        );
    }

    public function testBlockNotAllowedInInline()
    {
        $this->isInline = true;
        $this->assertResult(
            '<div>Not allowed.</div>', ''
        );
    }

    public function testBlockAllowedInNonInline()
    {
        $this->isInline = false;
        $this->assertResult(
            '<div>Allowed.</div>'
        );
    }

}

// vim: et sw=4 sts=4
