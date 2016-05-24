<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

class ItemsTest extends SwaggerTestCase
{

    public function testTypeArray()
    {
        $annotations = $this->parseComment('@SWG\Items(type="array")');
        $this->assertSwaggerLogEntryStartsWith('@SWG\Items() is required when @SWG\Items() has type "array" in ');
        $annotations[0]->validate();
    }

    public function testTypeObject()
    {
        $notAllowedInQuery = $this->parseComment('@SWG\Parameter(name="param",in="query",type="array",@SWG\Items(type="object"))');
        $this->assertSwaggerLogEntryStartsWith('@SWG\Items()->type="object" not allowed inside a @SWG\Parameter() must be "string", "number", "integer", "boolean", "array" in ');
        $notAllowedInQuery[0]->validate();
    }
}
