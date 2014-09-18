<?php

class HTMLPurifier_Strategy_ValidateAttributesTest extends
      HTMLPurifier_StrategyHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_ValidateAttributes();
    }

    public function testEmptyInput()
    {
        $this->assertResult('');
    }

    public function testRemoveIDByDefault()
    {
        $this->assertResult(
            '<div id="valid">Kill the ID.</div>',
            '<div>Kill the ID.</div>'
        );
    }

    public function testRemoveInvalidDir()
    {
        $this->assertResult(
            '<span dir="up-to-down">Bad dir.</span>',
            '<span>Bad dir.</span>'
        );
    }

    public function testPreserveValidClass()
    {
        $this->assertResult('<div class="valid">Valid</div>');
    }

    public function testSelectivelyRemoveInvalidClasses()
    {
        $this->config->set('HTML.Doctype', 'XHTML 1.1');
        $this->assertResult(
            '<div class="valid 0invalid">Keep valid.</div>',
            '<div class="valid">Keep valid.</div>'
        );
    }

    public function testPreserveTitle()
    {
        $this->assertResult(
            '<acronym title="PHP: Hypertext Preprocessor">PHP</acronym>'
        );
    }

    public function testAddXMLLang()
    {
        $this->assertResult(
            '<span lang="fr">La soupe.</span>',
            '<span lang="fr" xml:lang="fr">La soupe.</span>'
        );
    }

    public function testOnlyXMLLangInXHTML11()
    {
        $this->config->set('HTML.Doctype', 'XHTML 1.1');
        $this->assertResult(
            '<b lang="en">asdf</b>',
            '<b xml:lang="en">asdf</b>'
        );
    }

    public function testBasicURI()
    {
        $this->assertResult('<a href="http://www.google.com/">Google</a>');
    }

    public function testInvalidURI()
    {
        $this->assertResult(
            '<a href="javascript:badstuff();">Google</a>',
            '<a>Google</a>'
        );
    }

    public function testBdoAddMissingDir()
    {
        $this->assertResult(
            '<bdo>Go left.</bdo>',
            '<bdo dir="ltr">Go left.</bdo>'
        );
    }

    public function testBdoReplaceInvalidDirWithDefault()
    {
        $this->assertResult(
            '<bdo dir="blahblah">Invalid value!</bdo>',
            '<bdo dir="ltr">Invalid value!</bdo>'
        );
    }

    public function testBdoAlternateDefaultDir()
    {
        $this->config->set('Attr.DefaultTextDir', 'rtl');
        $this->assertResult(
            '<bdo>Go right.</bdo>',
            '<bdo dir="rtl">Go right.</bdo>'
        );
    }

    public function testRemoveDirWhenNotRequired()
    {
        $this->assertResult(
            '<span dir="blahblah">Invalid value!</span>',
            '<span>Invalid value!</span>'
        );
    }

    public function testTableAttributes()
    {
        $this->assertResult(
'<table frame="above" rules="rows" summary="A test table" border="2" cellpadding="5%" cellspacing="3" width="100%">
    <col align="right" width="4*" />
    <col charoff="5" align="char" width="*" />
    <tr valign="top">
        <th abbr="name">Fiddly name</th>
        <th abbr="price">Super-duper-price</th>
    </tr>
    <tr>
        <td abbr="carrot">Carrot Humungous</td>
        <td>$500.23</td>
    </tr>
    <tr>
        <td colspan="2">Taken off the market</td>
    </tr>
</table>'
        );
    }

    public function testColSpanIsNonZero()
    {
        $this->assertResult(
            '<col span="0" />',
            '<col />'
        );
    }

    public function testImgAddDefaults()
    {
        $this->config->set('Core.RemoveInvalidImg', false);
        $this->assertResult(
            '<img />',
            '<img src="" alt="Invalid image" />'
        );
    }

    public function testImgGenerateAlt()
    {
        $this->assertResult(
            '<img src="foobar.jpg" />',
            '<img src="foobar.jpg" alt="foobar.jpg" />'
        );
    }

    public function testImgAddDefaultSrc()
    {
        $this->config->set('Core.RemoveInvalidImg', false);
        $this->assertResult(
            '<img alt="pretty picture" />',
            '<img alt="pretty picture" src="" />'
        );
    }

    public function testImgRemoveNonRetrievableProtocol()
    {
        $this->config->set('Core.RemoveInvalidImg', false);
        $this->assertResult(
            '<img src="mailto:foo@example.com" />',
            '<img alt="mailto:foo@example.com" src="" />'
        );
    }

    public function testPreserveRel()
    {
        $this->config->set('Attr.AllowedRel', 'nofollow');
        $this->assertResult('<a href="foo" rel="nofollow" />');
    }

    public function testPreserveTarget()
    {
        $this->config->set('Attr.AllowedFrameTargets', '_top');
        $this->config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
        $this->assertResult('<a href="foo" target="_top" />');
    }

    public function testRemoveTargetWhenNotSupported()
    {
        $this->config->set('HTML.Doctype', 'XHTML 1.0 Strict');
        $this->config->set('Attr.AllowedFrameTargets', '_top');
        $this->assertResult(
            '<a href="foo" target="_top" />',
            '<a href="foo" />'
        );
    }

    public function testKeepAbsoluteCSSWidthAndHeightOnImg()
    {
        $this->assertResult(
            '<img src="" alt="" style="width:10px;height:10px;border:1px solid #000;" />'
        );
    }

    public function testRemoveLargeCSSWidthAndHeightOnImg()
    {
        $this->assertResult(
            '<img src="" alt="" style="width:10000000px;height:10000000px;border:1px solid #000;" />',
            '<img src="" alt="" style="border:1px solid #000;" />'
        );
    }

    public function testRemoveLargeCSSWidthAndHeightOnImgWithUserConf()
    {
        $this->config->set('CSS.MaxImgLength', '1px');
        $this->assertResult(
            '<img src="" alt="" style="width:1mm;height:1mm;border:1px solid #000;" />',
            '<img src="" alt="" style="border:1px solid #000;" />'
        );
    }

    public function testKeepLargeCSSWidthAndHeightOnImgWhenToldTo()
    {
        $this->config->set('CSS.MaxImgLength', null);
        $this->assertResult(
            '<img src="" alt="" style="width:10000000px;height:10000000px;border:1px solid #000;" />'
        );
    }

    public function testKeepPercentCSSWidthAndHeightOnImgWhenToldTo()
    {
        $this->config->set('CSS.MaxImgLength', null);
        $this->assertResult(
            '<img src="" alt="" style="width:100%;height:100%;border:1px solid #000;" />'
        );
    }

    public function testRemoveRelativeCSSWidthAndHeightOnImg()
    {
        $this->assertResult(
            '<img src="" alt="" style="width:10em;height:10em;border:1px solid #000;" />',
            '<img src="" alt="" style="border:1px solid #000;" />'
        );
    }

    public function testRemovePercentCSSWidthAndHeightOnImg()
    {
        $this->assertResult(
            '<img src="" alt="" style="width:100%;height:100%;border:1px solid #000;" />',
            '<img src="" alt="" style="border:1px solid #000;" />'
        );
    }

}

// vim: et sw=4 sts=4
