<?php

class HTMLPurifier_Strategy_MakeWellFormedTest extends HTMLPurifier_StrategyHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_MakeWellFormed();
    }

    public function testEmptyInput()
    {
        $this->assertResult('');
    }

    public function testWellFormedInput()
    {
        $this->assertResult('This is <b>bold text</b>.');
    }

    public function testUnclosedTagTerminatedByDocumentEnd()
    {
        $this->assertResult(
            '<b>Unclosed tag, gasp!',
            '<b>Unclosed tag, gasp!</b>'
        );
    }

    public function testUnclosedTagTerminatedByParentNodeEnd()
    {
        $this->assertResult(
            '<b><i>Bold and italic?</b>',
            '<b><i>Bold and italic?</i></b><i></i>'
        );
    }

    public function testRemoveStrayClosingTag()
    {
        $this->assertResult(
            'Unused end tags... recycle!</b>',
            'Unused end tags... recycle!'
        );
    }

    public function testConvertStartToEmpty()
    {
        $this->assertResult(
            '<br style="clear:both;">',
            '<br style="clear:both;" />'
        );
    }

    public function testConvertEmptyToStart()
    {
        $this->assertResult(
            '<div style="clear:both;" />',
            '<div style="clear:both;"></div>'
        );
    }

    public function testAutoCloseParagraph()
    {
        $this->assertResult(
            '<p>Paragraph 1<p>Paragraph 2',
            '<p>Paragraph 1</p><p>Paragraph 2</p>'
        );
    }

    public function testAutoCloseParagraphInsideDiv()
    {
        $this->assertResult(
            '<div><p>Paragraphs<p>In<p>A<p>Div</div>',
            '<div><p>Paragraphs</p><p>In</p><p>A</p><p>Div</p></div>'
        );
    }

    public function testAutoCloseListItem()
    {
        $this->assertResult(
            '<ol><li>Item 1<li>Item 2</ol>',
            '<ol><li>Item 1</li><li>Item 2</li></ol>'
        );
    }

    public function testAutoCloseColgroup()
    {
        $this->assertResult(
            '<table><colgroup><col /><tr></tr></table>',
            '<table><colgroup><col /></colgroup><tr></tr></table>'
        );
    }

    public function testAutoCloseMultiple()
    {
        $this->assertResult(
            '<b><span><div></div>asdf',
            '<b><span></span></b><div><b></b></div><b>asdf</b>'
        );
    }

    public function testUnrecognized()
    {
        $this->assertResult(
            '<asdf><foobar /><biddles>foo</asdf>',
            '<asdf><foobar /><biddles>foo</biddles></asdf>'
        );
    }

    public function testBlockquoteWithInline()
    {
        $this->config->set('HTML.Doctype', 'XHTML 1.0 Strict');
        $this->assertResult(
            // This is actually invalid, but will be fixed by
            // ChildDef_StrictBlockquote
            '<blockquote>foo<b>bar</b></blockquote>'
        );
    }

    public function testLongCarryOver()
    {
        $this->assertResult(
            '<b>asdf<div>asdf<i>df</i></div>asdf</b>',
            '<b>asdf</b><div><b>asdf<i>df</i></b></div><b>asdf</b>'
        );
    }

    public function testInterleaved()
    {
        $this->assertResult(
            '<u>foo<i>bar</u>baz</i>',
            '<u>foo<i>bar</i></u><i>baz</i>'
        );
    }

    public function testNestedOl()
    {
        $this->assertResult(
            '<ol><ol><li>foo</li></ol></ol>',
            '<ol><ol><li>foo</li></ol></ol>'
        );
    }

    public function testNestedUl()
    {
        $this->assertResult(
            '<ul><ul><li>foo</li></ul></ul>',
            '<ul><ul><li>foo</li></ul></ul>'
        );
    }

    public function testNestedOlWithStrangeEnding()
    {
        $this->assertResult(
            '<ol><li><ol><ol><li>foo</li></ol></li><li>foo</li></ol>',
            '<ol><li><ol><ol><li>foo</li></ol></ol></li><li>foo</li></ol>'
        );
    }

    public function testNoAutocloseIfNoParentsCanAccomodateTag()
    {
        $this->assertResult(
            '<table><tr><td><li>foo</li></td></tr></table>',
            '<table><tr><td>foo</td></tr></table>'
        );
    }

}

// vim: et sw=4 sts=4
