<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

class ExamplesTest extends SwaggerTestCase
{

    /**
     * Test the processed Examples against json files in ExamplesOutput.
     *
     * @dataProvider getExamples
     * @param string $example Example path
     * @param string $output Expected output (path to a json file)
     */
    public function testExample($example, $output)
    {
        $swagger = \Swagger\scan(__DIR__ . '/../Examples/' . $example);
//        die((string) $swagger);
        $this->assertSwaggerEqualsFile(__DIR__ . '/ExamplesOutput/' . $output, $swagger);
    }

    /**
     * dataProvider for testExample
     * @return array
     */
    public function getExamples()
    {
        return [
            ['petstore.swagger.io', 'petstore.swagger.io.json'],
            ['swagger-spec/petstore', 'petstore.json'],
            ['swagger-spec/petstore-simple', 'petstore-simple.json'],
            ['swagger-spec/petstore-with-external-docs', 'petstore-with-external-docs.json'],
        ];
    }
}
