<?php

class HTMLPurifier_ChildDef_OptionalTest extends HTMLPurifier_ChildDefHarness
{

    public function setUp()
    {
        parent::setUp();
        $this->obj = new HTMLPurifier_ChildDef_Optional('b | i');
    }

    public function testBasicUsage()
    {
        $this->assertResult('<b>Bold text</b><img />', '<b>Bold text</b>');
    }

    public function testRemoveForbiddenText()
    {
        $this->assertResult('Not allowed text', '');
    }

    public function testEmpty()
    {
        $this->assertResult('');
    }

    public function testWhitespace()
    {
        $this->assertResult(' ');
    }

    public function testMultipleWhitespace()
    {
        $this->assertResult('    ');
    }

}

// vim: et sw=4 sts=4
