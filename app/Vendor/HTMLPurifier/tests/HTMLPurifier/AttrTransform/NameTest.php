<?php

class HTMLPurifier_AttrTransform_NameTest extends HTMLPurifier_AttrTransformHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_Name();
    }

    public function testEmpty()
    {
        $this->assertResult( array() );
    }

    public function testTransformNameToID()
    {
        $this->assertResult(
            array('name' => 'free'),
            array('id' => 'free')
        );
    }

    public function testExistingIDOverridesName()
    {
        $this->assertResult(
            array('name' => 'tryit', 'id' => 'tobad'),
            array('id' => 'tobad')
        );
    }

}

// vim: et sw=4 sts=4
