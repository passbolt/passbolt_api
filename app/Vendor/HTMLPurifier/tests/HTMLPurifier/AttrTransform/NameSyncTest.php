<?php

class HTMLPurifier_AttrTransform_NameSyncTest extends HTMLPurifier_AttrTransformHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_NameSync();
        $this->accumulator = new HTMLPurifier_IDAccumulator();
        $this->context->register('IDAccumulator', $this->accumulator);
        $this->config->set('Attr.EnableID', true);
    }

    public function testEmpty()
    {
        $this->assertResult( array() );
    }

    public function testAllowSame()
    {
        $this->assertResult(
            array('name' => 'free', 'id' => 'free')
        );
    }

    public function testAllowDifferent()
    {
        $this->assertResult(
            array('name' => 'tryit', 'id' => 'thisgood')
        );
    }

    public function testCheckName()
    {
        $this->accumulator->add('notok');
        $this->assertResult(
            array('name' => 'notok', 'id' => 'ok'),
            array('id' => 'ok')
        );
    }

}

// vim: et sw=4 sts=4
