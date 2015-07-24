<?php

class HTMLPurifier_Injector_AutoParagraphTest extends HTMLPurifier_InjectorHarness
{

    public function setup()
    {
        parent::setup();
        $this->config->set('AutoFormat.AutoParagraph', true);
    }

    public function testSingleParagraph()
    {
        $this->assertResult(
            'Foobar',
            '<p>Foobar</p>'
        );
    }

    public function testSingleMultiLineParagraph()
    {
        $this->assertResult(
'Par 1
Par 1 still',
'<p>Par 1
Par 1 still</p>'
        );
    }

    public function testTwoParagraphs()
    {
        $this->assertResult(
'Par1

Par2',
"<p>Par1</p>

<p>Par2</p>"
        );
    }

    public function testTwoParagraphsWithLotsOfSpace()
    {
        $this->assertResult(
'Par1



Par2',
'<p>Par1</p>

<p>Par2</p>'
        );
    }

    public function testTwoParagraphsWithInlineElements()
    {
        $this->assertResult(
'<b>Par1</b>

<i>Par2</i>',
'<p><b>Par1</b></p>

<p><i>Par2</i></p>'
        );
    }

    public function testSingleParagraphThatLooksLikeTwo()
    {
        $this->assertResult(
'<b>Par1

Par2</b>',
'<p><b>Par1

Par2</b></p>'
        );
    }

    public function testAddParagraphAdjacentToParagraph()
    {
        $this->assertResult(
            'Par1<p>Par2</p>',
'<p>Par1</p>

<p>Par2</p>'
        );
    }

    public function testParagraphUnclosedInlineElement()
    {
        $this->assertResult(
            '<b>Par1',
            '<p><b>Par1</b></p>'
        );
    }

    public function testPreservePreTags()
    {
        $this->assertResult(
'<pre>Par1

Par1</pre>'
        );
    }

    public function testIgnoreTrailingWhitespace()
    {
        $this->assertResult(
'Par1

  ',
'<p>Par1</p>

'
        );
    }

    public function testDoNotParagraphBlockElements()
    {
        $this->assertResult(
'Par1

<div>Par2</div>

Par3',
'<p>Par1</p>

<div>Par2</div>

<p>Par3</p>'
        );
    }

    public function testParagraphTextAndInlineNodes()
    {
        $this->assertResult(
'Par<b>1</b>',
            '<p>Par<b>1</b></p>'
        );
    }

    public function testPreserveLeadingWhitespace()
    {
        $this->assertResult(
'

Par',
'

<p>Par</p>'
        );
    }

    public function testPreserveSurroundingWhitespace()
    {
        $this->assertResult(
'

Par

',
'

<p>Par</p>

'
        );
    }

    public function testParagraphInsideBlockNode()
    {
        $this->assertResult(
'<div>Par1

Par2</div>',
'<div><p>Par1</p>

<p>Par2</p></div>'
        );
    }

    public function testParagraphInlineNodeInsideBlockNode()
    {
        $this->assertResult(
'<div><b>Par1</b>

Par2</div>',
'<div><p><b>Par1</b></p>

<p>Par2</p></div>'
        );
    }

    public function testNoParagraphWhenOnlyOneInsideBlockNode()
    {
        $this->assertResult('<div>Par1</div>');
    }

    public function testParagraphTwoInlineNodesInsideBlockNode()
    {
        $this->assertResult(
'<div><b>Par1</b>

<i>Par2</i></div>',
'<div><p><b>Par1</b></p>

<p><i>Par2</i></p></div>'
        );
    }

    public function testPreserveInlineNodesInPreTag()
    {
        $this->assertResult(
'<pre><b>Par1</b>

<i>Par2</i></pre>'
        );
    }

    public function testSplitUpInternalsOfPTagInBlockNode()
    {
        $this->assertResult(
'<div><p>Foo

Bar</p></div>',
'<div><p>Foo</p>

<p>Bar</p></div>'
        );
    }

    public function testSplitUpInlineNodesInPTagInBlockNode()
    {
        $this->assertResult(
'<div><p><b>Foo</b>

<i>Bar</i></p></div>',
'<div><p><b>Foo</b></p>

<p><i>Bar</i></p></div>'
        );
    }

    public function testNoParagraphSingleInlineNodeInBlockNode()
    {
        $this->assertResult( '<div><b>Foo</b></div>' );
    }

    public function testParagraphInBlockquote()
    {
        $this->assertResult(
'<blockquote>Par1

Par2</blockquote>',
'<blockquote><p>Par1</p>

<p>Par2</p></blockquote>'
        );
    }

    public function testNoParagraphBetweenListItem()
    {
        $this->assertResult(
'<ul><li>Foo</li>

<li>Bar</li></ul>'
        );
    }

    public function testParagraphSingleElementWithSurroundingSpace()
    {
        $this->assertResult(
'<div>

Bar

</div>',
        '<div>

<p>Bar</p>

</div>'
        );
    }

    public function testIgnoreExtraSpaceWithLeadingInlineNode()
    {
        $this->assertResult(
'<b>Par1</b>a



Par2',
'<p><b>Par1</b>a</p>

<p>Par2</p>'
        );
    }

    public function testAbsorbExtraEndingPTag()
    {
        $this->assertResult(
'Par1

Par2</p>',
'<p>Par1</p>

<p>Par2</p>'
        );
    }

    public function testAbsorbExtraEndingDivTag()
    {
        $this->assertResult(
'Par1

Par2</div>',
'<p>Par1</p>

<p>Par2</p>'
        );
    }

    public function testDoNotParagraphSingleSurroundingSpaceInBlockNode()
    {
        $this->assertResult(
'<div>
Par1
</div>'
        );
    }

    public function testBlockNodeTextDelimeterInBlockNode()
    {
        $this->assertResult(
'<div>Par1

<div>Par2</div></div>',
'<div><p>Par1</p>

<div>Par2</div></div>'
        );
    }

    public function testBlockNodeTextDelimeterWithoutDoublespaceInBlockNode()
    {
        $this->assertResult(
'<div>Par1
<div>Par2</div></div>'
        );
    }

    public function testBlockNodeTextDelimeterWithoutDoublespace()
    {
        $this->assertResult(
'Par1
<div>Par2</div>',
'<p>Par1
</p>

<div>Par2</div>'
        );
    }

    public function testTwoParagraphsOfTextAndInlineNode()
    {
        $this->assertResult(
'Par1

<b>Par2</b>',
'<p>Par1</p>

<p><b>Par2</b></p>'
        );
    }

    public function testLeadingInlineNodeParagraph()
    {
        $this->assertResult(
'<img /> Foo',
'<p><img /> Foo</p>'
        );
    }

    public function testTrailingInlineNodeParagraph()
    {
        $this->assertResult(
'<li>Foo <a>bar</a></li>'
        );
    }

    public function testTwoInlineNodeParagraph()
    {
        $this->assertResult(
'<li><b>baz</b><a>bar</a></li>'
        );
    }

    public function testNoParagraphTrailingBlockNodeInBlockNode()
    {
        $this->assertResult(
'<div><div>asdf</div><b>asdf</b></div>'
        );
    }

    public function testParagraphTrailingBlockNodeWithDoublespaceInBlockNode()
    {
        $this->assertResult(
'<div><div>asdf</div>

<b>asdf</b></div>',
'<div><div>asdf</div>

<p><b>asdf</b></p></div>'
        );
    }

    public function testParagraphTwoInlineNodesAndWhitespaceNode()
    {
        $this->assertResult(
'<b>One</b> <i>Two</i>',
'<p><b>One</b> <i>Two</i></p>'
        );
    }

    public function testNoParagraphWithInlineRootNode()
    {
        $this->config->set('HTML.Parent', 'span');
        $this->assertResult(
'Par

Par2'
        );
    }

    public function testInlineAndBlockTagInDivNoParagraph()
    {
        $this->assertResult(
            '<div><code>bar</code> mmm <pre>asdf</pre></div>'
        );
    }

    public function testInlineAndBlockTagInDivNeedingParagraph()
    {
        $this->assertResult(
'<div><code>bar</code> mmm

<pre>asdf</pre></div>',
'<div><p><code>bar</code> mmm</p>

<pre>asdf</pre></div>'
        );
    }

    public function testTextInlineNodeTextThenDoubleNewlineNeedsParagraph()
    {
        $this->assertResult(
'<div>asdf <code>bar</code> mmm

<pre>asdf</pre></div>',
'<div><p>asdf <code>bar</code> mmm</p>

<pre>asdf</pre></div>'
        );
    }

    public function testUpcomingTokenHasNewline()
    {
        $this->assertResult(
'<div>Test<b>foo</b>bar<b>bing</b>bang

boo</div>',
'<div><p>Test<b>foo</b>bar<b>bing</b>bang</p>

<p>boo</p></div>'
);
    }

    public function testEmptyTokenAtEndOfDiv()
    {
        $this->assertResult(
'<div><p>foo</p>
</div>',
'<div><p>foo</p>
</div>'
);
    }

    public function testEmptyDoubleLineTokenAtEndOfDiv()
    {
        $this->assertResult(
'<div><p>foo</p>

</div>',
'<div><p>foo</p>

</div>'
);
    }

    public function testTextState11Root()
    {
        $this->assertResult('<div></div>   ');
    }

    public function testTextState11Element()
    {
        $this->assertResult(
"<div><div></div>

</div>");
    }

    public function testTextStateLikeElementState111NoWhitespace()
    {
        $this->assertResult('<div><p>P</p>Boo</div>', '<div><p>P</p>Boo</div>');
    }

    public function testElementState111NoWhitespace()
    {
        $this->assertResult('<div><p>P</p><b>Boo</b></div>', '<div><p>P</p><b>Boo</b></div>');
    }

    public function testElementState133()
    {
        $this->assertResult(
"<div><b>B</b><pre>Ba</pre>

Bar</div>",
"<div><b>B</b><pre>Ba</pre>

<p>Bar</p></div>"
);
    }

    public function testElementState22()
    {
        $this->assertResult(
            '<ul><li>foo</li></ul>'
        );
    }

    public function testElementState311()
    {
        $this->assertResult(
            '<p>Foo</p><b>Bar</b>',
'<p>Foo</p>

<p><b>Bar</b></p>'
        );
    }

    public function testAutoClose()
    {
        $this->assertResult(
            '<p></p>
<hr />'
        );
    }

    public function testErrorNeeded()
    {
        $this->config->set('HTML.Allowed', 'b');
        $this->expectError('Cannot enable AutoParagraph injector because p is not allowed');
        $this->assertResult('<b>foobar</b>');
    }

    public function testParentElement()
    {
        $this->config->set('HTML.Allowed', 'p,ul,li');
        $this->assertResult('Foo<ul><li>Bar</li></ul>', "<p>Foo</p>\n\n<ul><li>Bar</li></ul>");
    }

}

// vim: et sw=4 sts=4
