<?php

class HTMLPurifier_AttrTransform_InputTest extends HTMLPurifier_AttrTransformHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_Input();
    }

    public function testEmptyInput()
    {
        $this->assertResult(array());
    }

    public function testInvalidCheckedWithEmpty()
    {
        $this->assertResult(array('checked' => 'checked'), array());
    }

    public function testInvalidCheckedWithPassword()
    {
        $this->assertResult(array(
            'checked' => 'checked',
            'type' => 'password'
        ), array(
            'type' => 'password'
        ));
    }

    public function testValidCheckedWithUcCheckbox()
    {
        $this->assertResult(array(
            'checked' => 'checked',
            'type' => 'CHECKBOX',
            'value' => 'bar',
        ));
    }

    public function testInvalidMaxlength()
    {
        $this->assertResult(array(
            'maxlength' => '10',
            'type' => 'checkbox',
            'value' => 'foo',
        ), array(
            'type' => 'checkbox',
            'value' => 'foo',
        ));
    }

    public function testValidMaxLength()
    {
        $this->assertResult(array(
            'maxlength' => '10',
        ));
    }

    // these two are really bad test-cases

    public function testSizeWithCheckbox()
    {
        $this->assertResult(array(
            'type' => 'checkbox',
            'value' => 'foo',
            'size' => '100px',
        ), array(
            'type' => 'checkbox',
            'value' => 'foo',
            'size' => '100',
        ));
    }

    public function testSizeWithText()
    {
        $this->assertResult(array(
            'type' => 'password',
            'size' => '100px', // spurious value, to indicate no validation takes place
        ), array(
            'type' => 'password',
            'size' => '100px',
        ));
    }

    public function testInvalidSrc()
    {
        $this->assertResult(array(
            'src' => 'img.png',
        ), array());
    }

    public function testMissingValue()
    {
        $this->assertResult(array(
            'type' => 'checkbox',
        ), array(
            'type' => 'checkbox',
            'value' => '',
        ));
    }

}

// vim: et sw=4 sts=4
