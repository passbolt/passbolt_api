<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Annotations\Info;
use Swagger\Annotations\Swagger;
use Swagger\Analysis;
use Swagger\Processors\MergeIntoSwagger;

class MergeIntoSwaggerTest extends SwaggerTestCase
{

    public function testProcessor()
    {
        $swagger = new Swagger([]);
        $info = new Info([]);
        $analysis = new Analysis([
            $swagger,
            $info
        ]);
        $this->assertNull($analysis->swagger);
        $this->assertNull($swagger->info);
        $analysis->process(new MergeIntoSwagger());
        $this->assertInstanceOf('Swagger\Annotations\Swagger', $analysis->swagger);
        $this->assertSame($info, $swagger->info);
        $this->assertCount(0, $analysis->unmerged()->annotations);
    }
}
