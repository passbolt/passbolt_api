<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

class CommandlineInterfaceTest extends SwaggerTestCase
{

    protected function setUp()
    {
        if (defined('HHVM_VERSION')) {
            $this->markTestSkipped();
        }
        parent::setUp();
    }

    public function testStdout()
    {
        exec(__DIR__ . '/../bin/swagger --stdout ' . escapeshellarg(__DIR__ . '/../Examples/swagger-spec/petstore-simple') . ' 2> /dev/null', $output, $retval);
        $this->assertSame(0, $retval);
        $json = json_decode(implode("\n", $output));
        $this->assertSame(JSON_ERROR_NONE, json_last_error());
        $this->compareOutput($json);
    }

    public function testOutputTofile()
    {
        $filename = sys_get_temp_dir() . '/swagger-php-clitest.json';
        exec(__DIR__ . '/../bin/swagger -o ' . escapeshellarg($filename) . ' ' . escapeshellarg(__DIR__ . '/../Examples/swagger-spec/petstore-simple') . ' 2> /dev/null', $output, $retval);
        $this->assertSame(0, $retval);
        $this->assertCount(0, $output, 'No output to stdout');
        $contents = file_get_contents($filename);
        unlink($filename);
        $json = json_decode($contents);
        $this->assertSame(JSON_ERROR_NONE, json_last_error());
        $this->compareOutput($json);
    }

    private function compareOutput($actual)
    {
        $expected = json_decode(file_get_contents(__DIR__ . '/ExamplesOutput/petstore-simple.json'));
        $expectedJson = json_encode($this->sorted($expected, 'petstore-simple.json'), JSON_PRETTY_PRINT);
        $actualJson = json_encode($this->sorted($actual, 'Swagger CLI'), JSON_PRETTY_PRINT);
        $this->assertEquals($expectedJson, $actualJson);
    }
}
