<?php

class HTMLPurifier_Strategy_FixNestingTest extends HTMLPurifier_StrategyHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_FixNesting();
    }

    public function testPreserveInlineInRoot()
    {
        $this->assertResult('<b>Bold text</b>');
    }

    public function testPreserveInlineAndBlockInRoot()
    {
        $this->assertResult('<a href="about:blank">Blank</a><div>Block</div>');
    }

    public function testRemoveBlockInInline()
    {
        $this->assertResult(
            '<b><div>Illegal div.</div></b>',
            '<b>Illegal div.</b>'
        );
    }

    public function testRemoveNodeWithMissingRequiredElements()
    {
        $this->assertResult('<ul></ul>', '');
    }

    public function testListHandleIllegalPCDATA()
    {
        $this->assertResult(
            '<ul>Illegal text<li>Legal item</li></ul>',
            '<ul><li>Illegal text</li><li>Legal item</li></ul>'
        );
    }

    public function testRemoveIllegalPCDATA()
    {
        $this->assertResult(
            '<table><tr>Illegal text<td></td></tr></table>',
            '<table><tr><td></td></tr></table>'
        );
    }

    public function testCustomTableDefinition()
    {
        $this->assertResult('<table><tr><td>Cell 1</td></tr></table>');
    }

    public function testRemoveEmptyTable()
    {
        $this->assertResult('<table></table>', '');
    }

    public function testChameleonRemoveBlockInNodeInInline()
    {
        $this->assertResult(
          '<span><ins><div>Not allowed!</div></ins></span>',
          '<span><ins>Not allowed!</ins></span>'
        );
    }

    public function testChameleonRemoveBlockInBlockNodeWithInlineContent()
    {
        $this->assertResult(
          '<h1><ins><div>Not allowed!</div></ins></h1>',
          '<h1><ins>Not allowed!</ins></h1>'
        );
    }

    public function testNestedChameleonRemoveBlockInNodeWithInlineContent()
    {
        $this->assertResult(
          '<h1><ins><del><div>Not allowed!</div></del></ins></h1>',
          '<h1><ins><del>Not allowed!</del></ins></h1>'
        );
    }

    public function testNestedChameleonPreserveBlockInBlock()
    {
        $this->assertResult(
          '<div><ins><del><div>Allowed!</div></del></ins></div>'
        );
    }

    public function testExclusionsIntegration()
    {
        // test exclusions
        $this->assertResult(
          '<a><span><a>Not allowed</a></span></a>',
          '<a><span></span></a>'
        );
    }

    public function testPreserveInlineNodeInInlineRootNode()
    {
        $this->config->set('HTML.Parent', 'span');
        $this->assertResult('<b>Bold</b>');
    }

    public function testRemoveBlockNodeInInlineRootNode()
    {
        $this->config->set('HTML.Parent', 'span');
        $this->assertResult('<div>Reject</div>', 'Reject');
   }

   public function testInvalidParentError()
   {
        // test fallback to div
        $this->config->set('HTML.Parent', 'obviously-impossible');
        $this->config->set('Cache.DefinitionImpl', null);
        $this->expectError('Cannot use unrecognized element as parent');
        $this->assertResult('<div>Accept</div>');
    }

    public function testCascadingRemovalOfNodesMissingRequiredChildren()
    {
        $this->assertResult('<table><tr></tr></table>', '');
    }

    public function testCascadingRemovalSpecialCaseCannotScrollOneBack()
    {
        $this->assertResult('<table><tr></tr><tr></tr></table>', '');
    }

    public function testLotsOfCascadingRemovalOfNodes()
    {
        $this->assertResult('<table><tbody><tr></tr><tr></tr></tbody><tr></tr><tr></tr></table>', '');
    }

    public function testAdjacentRemovalOfNodeMissingRequiredChildren()
    {
        $this->assertResult('<table></table><table></table>', '');
    }

    public function testStrictBlockquoteInHTML401()
    {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult('<blockquote>text</blockquote>', '<blockquote><p>text</p></blockquote>');
    }

    public function testDisabledExcludes()
    {
        $this->config->set('Core.DisableExcludes', true);
        $this->assertResult('<pre><font><font></font></font></pre>');
    }

}

// vim: et sw=4 sts=4
