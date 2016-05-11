<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Analysis;
use Swagger\Annotations\Get;
use Swagger\Annotations\Path;
use Swagger\Annotations\Post;
use Swagger\Annotations\Swagger;
use Swagger\Processors\BuildPaths;
use Swagger\Processors\MergeIntoSwagger;

class BuildPathsTest extends SwaggerTestCase
{

    public function testMergePathsWithSamePath()
    {
        $swagger = new Swagger([]);
        $swagger->paths = [
            new Path(['path' => '/comments']),
            new Path(['path' => '/comments'])
        ];
        $analysis = new Analysis([$swagger]);
        $analysis->swagger = $swagger;
        $analysis->process(new BuildPaths());
        $this->assertCount(1, $swagger->paths);
        $this->assertSame('/comments', $swagger->paths[0]->path);
    }

    public function testMergeOperationsWithSamePath()
    {
        $swagger = new Swagger([]);
        $analysis = new Analysis([
            $swagger,
            new Get(['path' => '/comments']),
            new Post(['path' => '/comments'])
        ]);
        $analysis->process(new MergeIntoSwagger());
        $analysis->process(new BuildPaths());
        $this->assertCount(1, $swagger->paths);
        $path = $swagger->paths[0];
        $this->assertSame('/comments', $path->path);
        $this->assertInstanceOf('\Swagger\Annotations\Path', $path);
        $this->assertInstanceOf('\Swagger\Annotations\Get', $path->get);
        $this->assertInstanceOf('\Swagger\Annotations\Post', $path->post);
        $this->assertNull($path->put);
    }
}
