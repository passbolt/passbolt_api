<?php

class HTMLPurifier_AttrTransform_ImgSpaceTest extends HTMLPurifier_AttrTransformHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_ImgSpace('vspace');
    }

    public function testEmptyInput()
    {
        $this->assertResult( array() );
    }

    public function testVerticalBasicUsage()
    {
        $this->assertResult(
            array('vspace' => '1'),
            array('style' => 'margin-top:1px;margin-bottom:1px;')
        );
    }

    public function testLenientHandlingOfInvalidInput()
    {
        $this->assertResult(
            array('vspace' => '10%'),
            array('style' => 'margin-top:10%px;margin-bottom:10%px;')
        );
    }

    public function testPrependNewCSS()
    {
        $this->assertResult(
            array('vspace' => '23', 'style' => 'font-weight:bold;'),
            array('style' => 'margin-top:23px;margin-bottom:23px;font-weight:bold;')
        );
    }

    public function testHorizontalBasicUsage()
    {
        $this->obj = new HTMLPurifier_AttrTransform_ImgSpace('hspace');
        $this->assertResult(
            array('hspace' => '1'),
            array('style' => 'margin-left:1px;margin-right:1px;')
        );
    }

    public function testInvalidConstructionParameter()
    {
        $this->expectError('ispace is not valid space attribute');
        $this->obj = new HTMLPurifier_AttrTransform_ImgSpace('ispace');
        $this->assertResult(
            array('ispace' => '1'),
            array()
        );
    }

}

// vim: et sw=4 sts=4
