<?php

namespace Swaggertests;

use Swagger\Annotations;
use Swagger\Serializer;

class SerializerTest extends SwaggerTestCase
{
    private function getExpected()
    {
        $path = new Annotations\Path([]);
        $path->path = '/products';
        $path->post = new Annotations\Post([]);
        $path->post->tags = ['products'];
        $path->post->summary = 's1';
        $path->post->description = 'd1';
        $path->post->consumes = ['application/json'];
        $path->post->produces = ['application/json'];

        $param = new Annotations\Parameter([]);
        $param->in = 'body';
        $param->description = 'data in body';
        $param->required = true;
        $param->type = 'object';
        $param->x = [];
        $param->x['repository'] = 'abc';
        $path->post->parameters = [$param];

        $resp = new Annotations\Response([]);
        $resp->response = '200';
        $resp->x = [];
        $resp->x['repository'] = 'def';
        $path->post->responses = [$resp];

        $expected = new Annotations\Swagger([]);
        $expected->swagger = '2.0';
        $expected->paths = [
            $path,
        ];

        return $expected;
    }

    public function testDeserializeAnnotation()
    {
        $serializer = new Serializer();

        $json = <<<JSON
{
  "swagger": "2.0",
  "paths": {
    "/products": {
      "post": {
        "tags": [
          "products"
        ],
        "summary": "s1",
        "description": "d1",
        "consumes": [
          "application/json"
        ],
        "produces": [
          "application/json"
        ],
        "parameters": [
          {
            "in": "body",
            "description": "data in body",
            "required": true,
            "type": "object",
            "x-repository": "abc"
          }
        ],
        "responses": {
          "200": {
            "x-repository": "def"
          }
        }
      }
    }
  }
}
JSON;

        $annotation = $serializer->deserialize($json, 'Swagger\Annotations\Swagger');

        $this->assertInstanceOf('Swagger\Annotations\Swagger', $annotation);
        $this->assertJsonStringEqualsJsonString(
            $annotation->__toString(),
            $this->getExpected()->__toString()
        );
    }
}
