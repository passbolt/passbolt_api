<?php

class HTMLPurifier_ConfigSchema_ValidatorAtomTest extends UnitTestCase
{

    protected function expectValidationException($msg)
    {
        $this->expectException(new HTMLPurifier_ConfigSchema_Exception($msg));
    }

    protected function makeAtom($value)
    {
        $obj = new stdClass();
        $obj->property = $value;
        // Note that 'property' and 'context' are magic wildcard values
        return new HTMLPurifier_ConfigSchema_ValidatorAtom('context', $obj, 'property');
    }

    public function testAssertIsString()
    {
        $this->makeAtom('foo')->assertIsString();
    }

    public function testAssertIsStringFail()
    {
        $this->expectValidationException("Property in context must be a string");
        $this->makeAtom(3)->assertIsString();
    }

    public function testAssertNotNull()
    {
        $this->makeAtom('foo')->assertNotNull();
    }

    public function testAssertNotNullFail()
    {
        $this->expectValidationException("Property in context must not be null");
        $this->makeAtom(null)->assertNotNull();
    }

    public function testAssertAlnum()
    {
        $this->makeAtom('foo2')->assertAlnum();
    }

    public function testAssertAlnumFail()
    {
        $this->expectValidationException("Property in context must be alphanumeric");
        $this->makeAtom('%a')->assertAlnum();
    }

    public function testAssertAlnumFailIsString()
    {
        $this->expectValidationException("Property in context must be a string");
        $this->makeAtom(3)->assertAlnum();
    }

    public function testAssertNotEmpty()
    {
        $this->makeAtom('foo')->assertNotEmpty();
    }

    public function testAssertNotEmptyFail()
    {
        $this->expectValidationException("Property in context must not be empty");
        $this->makeAtom('')->assertNotEmpty();
    }

    public function testAssertIsBool()
    {
        $this->makeAtom(false)->assertIsBool();
    }

    public function testAssertIsBoolFail()
    {
        $this->expectValidationException("Property in context must be a boolean");
        $this->makeAtom('0')->assertIsBool();
    }

    public function testAssertIsArray()
    {
        $this->makeAtom(array())->assertIsArray();
    }

    public function testAssertIsArrayFail()
    {
        $this->expectValidationException("Property in context must be an array");
        $this->makeAtom('asdf')->assertIsArray();
    }


    public function testAssertIsLookup()
    {
        $this->makeAtom(array('foo' => true))->assertIsLookup();
    }

    public function testAssertIsLookupFail()
    {
        $this->expectValidationException("Property in context must be a lookup array");
        $this->makeAtom(array('foo' => 4))->assertIsLookup();
    }

    public function testAssertIsLookupFailIsArray()
    {
        $this->expectValidationException("Property in context must be an array");
        $this->makeAtom('asdf')->assertIsLookup();
    }
}

// vim: et sw=4 sts=4
