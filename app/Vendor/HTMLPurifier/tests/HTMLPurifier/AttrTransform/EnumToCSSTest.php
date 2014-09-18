<?php

class HTMLPurifier_AttrTransform_EnumToCSSTest extends HTMLPurifier_AttrTransformHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
            'left'  => 'text-align:left;',
            'right' => 'text-align:right;'
        ));
    }

    public function testEmptyInput()
    {
        $this->assertResult( array() );
    }

    public function testPreserveArraysWithoutInterestingAttributes()
    {
        $this->assertResult( array('style' => 'font-weight:bold;') );
    }

    public function testConvertAlignLeft()
    {
        $this->assertResult(
            array('align' => 'left'),
            array('style' => 'text-align:left;')
        );
    }

    public function testConvertAlignRight()
    {
        $this->assertResult(
            array('align' => 'right'),
            array('style' => 'text-align:right;')
        );
    }

    public function testRemoveInvalidAlign()
    {
        $this->assertResult(
            array('align' => 'invalid'),
            array()
        );
    }

    public function testPrependNewCSS()
    {
        $this->assertResult(
            array('align' => 'left', 'style' => 'font-weight:bold;'),
            array('style' => 'text-align:left;font-weight:bold;')
        );

    }

    public function testCaseInsensitive()
    {
        $this->obj = new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
            'right' => 'text-align:right;'
        ));
        $this->assertResult(
            array('align' => 'RIGHT'),
            array('style' => 'text-align:right;')
        );
    }

    public function testCaseSensitive()
    {
        $this->obj = new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
            'right' => 'text-align:right;'
        ), true);
        $this->assertResult(
            array('align' => 'RIGHT'),
            array()
        );
    }

}

// vim: et sw=4 sts=4
