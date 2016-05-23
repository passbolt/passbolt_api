<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Context;
use Swagger\Analyser;

class AnalyserTest extends SwaggerTestCase
{

    public function testParseContents()
    {
        $annotations = $this->parseComment('@SWG\Parameter(description="This is my parameter")');
        $this->assertInternalType('array', $annotations);
        $parameter = $annotations[0];
        $this->assertInstanceOf('Swagger\Annotations\Parameter', $parameter);
        $this->assertSame('This is my parameter', $parameter->description);
    }

    public function testDeprecatedAnnotationWarning()
    {
        $this->assertSwaggerLogEntryStartsWith('The annotation @SWG\\Resource() is deprecated.');
        $annotations = $this->parseComment('@SWG\Resource()');
    }
}
