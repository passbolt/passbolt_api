<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

class AugmentParameterTest extends SwaggerTestCase
{

    public function testAugmentParameter()
    {
        $swagger = \Swagger\scan(__DIR__ . '/Fixtures/UsingRefs.php');
        $this->assertCount(1, $swagger->parameters, 'Swagger contains 1 reusable parameter specification');
        $this->assertEquals('ItemName', $swagger->parameters[0]->parameter, 'When no @SWG\Parameter()->parameter is specified, use @SWG\Parameter()->name');
    }
}
