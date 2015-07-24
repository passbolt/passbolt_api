<?php

class HTMLPurifier_ChildDef_ListTest extends HTMLPurifier_ChildDefHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_ChildDef_List();
    }

    public function testEmptyInput()
    {
        $this->assertResult('', false);
    }

    public function testSingleLi()
    {
        $this->assertResult('<li />');
    }

    public function testSomeLi()
    {
        $this->assertResult('<li>asdf</li><li />');
    }

    public function testOlAtBeginning()
    {
        $this->assertResult('<ol />', '<li><ol /></li>');
    }

    public function testOlAtBeginningWithOtherJunk()
    {
        $this->assertResult('<ol /><li />', '<li><ol /></li><li />');
    }

    public function testOlInMiddle()
    {
        $this->assertResult('<li>Foo</li><ol><li>Bar</li></ol>', '<li>Foo<ol><li>Bar</li></ol></li>');
    }

    public function testMultipleOl()
    {
        $this->assertResult('<li /><ol /><ol />', '<li><ol /><ol /></li>');
    }

    public function testUlAtBeginning()
    {
        $this->assertResult('<ul />', '<li><ul /></li>');
    }

}

// vim: et sw=4 sts=4
