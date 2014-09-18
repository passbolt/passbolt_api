<?php

class   HTMLPurifier_ChildDef_StrictBlockquoteTest
extends HTMLPurifier_ChildDefHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_ChildDef_StrictBlockquote('div | p');
    }

    public function testEmptyInput()
    {
        $this->assertResult('');
    }

    public function testPreserveValidP()
    {
        $this->assertResult('<p>Valid</p>');
    }

    public function testPreserveValidDiv()
    {
        $this->assertResult('<div>Still valid</div>');
    }

    public function testWrapTextWithP()
    {
        $this->assertResult('Needs wrap', '<p>Needs wrap</p>');
    }

    public function testNoWrapForWhitespaceOrValidElements()
    {
        $this->assertResult('<p>Do not wrap</p>    <p>Whitespace</p>');
    }

    public function testWrapTextNextToValidElements()
    {
        $this->assertResult(
               'Wrap'. '<p>Do not wrap</p>',
            '<p>Wrap</p><p>Do not wrap</p>'
        );
    }

    public function testWrapInlineElements()
    {
        $this->assertResult(
            '<p>Do not</p>'.'<b>Wrap</b>',
            '<p>Do not</p><p><b>Wrap</b></p>'
        );
    }

    public function testWrapAndRemoveInvalidTags()
    {
        $this->assertResult(
            '<li>Not allowed</li>Paragraph.<p>Hmm.</p>',
            '<p>Not allowedParagraph.</p><p>Hmm.</p>'
        );
    }

    public function testWrapComplicatedSring()
    {
        $this->assertResult(
            $var = 'He said<br />perhaps<br />we should <b>nuke</b> them.',
            "<p>$var</p>"
        );
    }

    public function testWrapAndRemoveInvalidTagsComplex()
    {
        $this->assertResult(
            '<foo>Bar</foo><bas /><b>People</b>Conniving.'. '<p>Fools!</p>',
              '<p>Bar'.          '<b>People</b>Conniving.</p><p>Fools!</p>'
        );
    }

    public function testAlternateWrapper()
    {
        $this->config->set('HTML.BlockWrapper', 'div');
        $this->assertResult('Needs wrap', '<div>Needs wrap</div>');

    }

    public function testError()
    {
        $this->expectError('Cannot use non-block element as block wrapper');
        $this->obj = new HTMLPurifier_ChildDef_StrictBlockquote('div | p');
        $this->config->set('HTML.BlockWrapper', 'dav');
        $this->config->set('Cache.DefinitionImpl', null);
        $this->assertResult('Needs wrap', '<p>Needs wrap</p>');
    }

}

// vim: et sw=4 sts=4
