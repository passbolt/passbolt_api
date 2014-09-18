<?php

class HTMLPurifier_Injector_RemoveEmptyTest extends HTMLPurifier_InjectorHarness
{

    public function setup()
    {
        parent::setup();
        $this->config->set('AutoFormat.RemoveEmpty', true);
    }

    public function testPreserve()
    {
        $this->assertResult('<b>asdf</b>');
    }

    public function testRemove()
    {
        $this->assertResult('<b></b>', '');
    }

    public function testRemoveWithSpace()
    {
        $this->assertResult('<b>   </b>', '');
    }

    public function testRemoveWithAttr()
    {
        $this->assertResult('<b class="asdf"></b>', '');
    }

    public function testRemoveIdAndName()
    {
        $this->assertResult('<a id="asdf" name="asdf"></a>', '');
    }

    public function testPreserveColgroup()
    {
        $this->assertResult('<colgroup></colgroup>');
    }

    public function testPreserveId()
    {
        $this->config->set('Attr.EnableID', true);
        $this->assertResult('<a id="asdf"></a>');
    }

    public function testPreserveName()
    {
        $this->config->set('Attr.EnableID', true);
        $this->assertResult('<a name="asdf"></a>');
    }

    public function testRemoveNested()
    {
        $this->assertResult('<b><i></i></b>', '');
    }

    public function testRemoveNested2()
    {
        $this->assertResult('<b><i><u></u></i></b>', '');
    }

    public function testRemoveNested3()
    {
        $this->assertResult('<b> <i> <u> </u> </i> </b>', '');
    }

    public function testRemoveNbsp()
    {
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $this->assertResult('<b>&nbsp;</b>', '');
    }

    public function testRemoveNbspMix()
    {
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $this->assertResult('<b>&nbsp;   &nbsp;</b>', '');
    }

    public function testDontRemoveNbsp()
    {
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $this->assertResult('<td>&nbsp;</b>', "<td>\xC2\xA0</td>");
    }

    public function testRemoveNbspExceptionsSpecial()
    {
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $this->config->set('AutoFormat.RemoveEmpty.RemoveNbsp.Exceptions', 'b');
        $this->assertResult('<b>&nbsp;</b>', "<b>\xC2\xA0</b>");
    }

}

// vim: et sw=4 sts=4
