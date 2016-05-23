<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

class ResponseTest extends SwaggerTestCase
{

    public function testMisspelledDefault()
    {
        $annotations = $this->parseComment('@SWG\Get(@SWG\Response(response="Default", description="description"))');
        $this->assertSwaggerLogEntryStartsWith('Invalid value "Default" for @SWG\Response()->response, expecting "default" or a HTTP Status Code in ');
        $annotations[0]->validate();
    }
}
