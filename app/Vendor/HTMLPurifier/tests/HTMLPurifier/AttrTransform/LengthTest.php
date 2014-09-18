<?php

class HTMLPurifier_AttrTransform_LengthTest extends HTMLPurifier_AttrTransformHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_Length('width');
    }

    public function testEmptyInput()
    {
        $this->assertResult( array() );
    }

    public function testTransformPixel()
    {
        $this->assertResult(
            array('width' => '10'),
            array('style' => 'width:10px;')
        );
    }

    public function testTransformPercentage()
    {
        $this->assertResult(
            array('width' => '10%'),
            array('style' => 'width:10%;')
        );
    }

    public function testPrependNewCSS()
    {
        $this->assertResult(
            array('width' => '10%', 'style' => 'font-weight:bold'),
            array('style' => 'width:10%;font-weight:bold')
        );
    }

    public function testLenientTreatmentOfInvalidInput()
    {
        $this->assertResult(
            array('width' => 'asdf'),
            array('style' => 'width:asdf;')
        );
    }

}

// vim: et sw=4 sts=4
