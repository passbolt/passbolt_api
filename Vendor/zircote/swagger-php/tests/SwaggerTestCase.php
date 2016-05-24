<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Closure;
use PHPUnit_Framework_TestCase;
use stdClass;
use Exception;
use Swagger\Annotations\AbstractAnnotation;
use Swagger\Annotations\Swagger;
use Swagger\Context;
use Swagger\Logger;
use Swagger\Analyser;

class SwaggerTestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    private $expectedLogMessages;

    /**
     * @var Closure
     */
    private $originalLogger;

    /**
     *
     * @param string $expectedFile File containing the excepted json.
     * @param Swagger $actualSwagger
     * @param string $message
     */
    public function assertSwaggerEqualsFile($expectedFile, $actualSwagger, $message = '')
    {
        $expected = json_decode(file_get_contents($expectedFile));
        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE) {
            $this->fail('File: "' . $expectedFile . '" doesn\'t contain valid json, error ' . $error);
        }
        $json = json_encode($actualSwagger);
        if ($json === false) {
            $this->fail('Failed to encode swagger object');
        }
        $actual = json_decode($json);
        $expectedJson = json_encode($this->sorted($expected, $expectedFile), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $actualJson = json_encode($this->sorted($actual, 'Swagger'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $this->assertEquals($expectedJson, $actualJson, $message);
    }

    public function assertSwaggerLog($expectedEntry, $expectedType, $message = '')
    {
        $this->expectedLogMessages[] = function ($actualEntry, $actualType) use ($expectedEntry, $expectedType, $message) {
            $this->assertSame($expectedEntry, $actualEntry, $message);
            $this->assertSame($expectedType, $actualType, $message);
        };
    }

    public function assertSwaggerLogType($expectedType, $message = '')
    {
        $this->expectedLogMessages[] = function ($entry, $actualType) use ($expectedType, $message) {
            $this->assertSame($expectedType, $actualType, $message);
        };
    }

    public function assertSwaggerLogEntry($expectedEntry, $message = '')
    {
        $this->expectedLogMessages[] = function ($actualEntry, $type) use ($expectedEntry, $message) {
            $this->assertSame($expectedEntry, $actualEntry, $message);
        };
    }

    public function assertSwaggerLogEntryStartsWith($entryPrefix, $message = '')
    {
        $this->expectedLogMessages[] = function ($entry, $type) use ($entryPrefix, $message) {
            if ($entry instanceof Exception) {
                $entry = $entry->getMessage();
            }
            $this->assertStringStartsWith($entryPrefix, $entry, $message);
        };
    }

    protected function setUp()
    {
        $this->expectedLogMessages = [];
        $this->originalLogger = Logger::getInstance()->log;
        Logger::getInstance()->log = function ($entry, $type) {
            if (count($this->expectedLogMessages)) {
                $assertion = array_shift($this->expectedLogMessages);
                $assertion($entry, $type);
            } else {
                $map = [
                    E_USER_NOTICE => 'notice',
                    E_USER_WARNING => 'warning',
                ];
                if (isset($map[$type])) {
                    $this->fail('Unexpected \Swagger\Logger::' . $map[$type] . '("' . $entry . '")');
                } else {
                    $this->fail('Unexpected \Swagger\Logger->getInstance()->log("' . $entry . '",' . $type . ')');
                }
            }
        };
        parent::setUp();
    }

    protected function tearDown()
    {
        $this->assertCount(0, $this->expectedLogMessages, count($this->expectedLogMessages) . ' Swagger\Logger messages were not triggered');
        Logger::getInstance()->log = $this->originalLogger;
        parent::tearDown();
    }

    /**
     *
     * @param string $comment Contents of a comment block
     * @return AbstractAnnotation[]
     */
    protected function parseComment($comment)
    {
        $analyser = new Analyser();
        $context = Context::detect(1);
        $context->line -= 1; // correct generated lines: "<?php\n" and "/**\n"
        return $analyser->fromComment("<?php\n/**\n * " . implode("\n * ", explode("\n", $comment)) . "\n*/", $context);
    }

    /**
     * Create a Swagger object with Info.
     * (So it will pass validation.)
     */
    protected function createSwaggerWithInfo()
    {
        $swagger = new Swagger([
            'info' => new \Swagger\Annotations\Info([
                'title' => 'Swagger-PHP Test-API',
                'version' => 'test',
                '_context' => new Context(['unittest' => true])
            ]),
            '_context' => new Context(['unittest' => true])
        ]);
        return $swagger;
    }

    /**
     * Sorts the object to improve matching and debugging the differences.
     * Used by assertSwaggerEqualsFile
     * @param stdClass $object
     * @param string   $origin
     * @return stdClass The sorted object
     */
    protected function sorted(stdClass $object, $origin = 'unknown')
    {
        static $sortMap = null;
        if ($sortMap === null) {
            $sortMap = [
                // property -> algorithm
                'parameters' => function ($a, $b) {
                    return strcasecmp($a->name, $b->name);
                },
//                'responses' => function ($a, $b) {
//                    return strcasecmp($a->name, $b->name);
//                },
                'headers' => function ($a, $b) {
                    return strcasecmp($a->header, $b->header);
                },
                'tags' => function ($a, $b) {
                    return strcasecmp($a->name, $b->name);
                },
                'allOf' => function ($a, $b) {
                    return strcasecmp(implode(',', array_keys(get_object_vars($a))), implode(',', array_keys(get_object_vars($b))));
                },
                'security' => function ($a, $b) {
                    return strcasecmp(implode(',', array_keys(get_object_vars($a))), implode(',', array_keys(get_object_vars($b))));
                }
            ];
        }
        $data = unserialize(serialize((array) $object));
        ksort($data);
        foreach ($data as $property => $value) {
            if (is_object($value)) {
                $data[$property] = $this->sorted($value, $origin . '->' . $property);
            } elseif (is_array($value)) {
                if (count($value) > 1) {
                    if (gettype($value[0]) === 'string') {
                        $sortFn = 'strcasecmp';
                    } else {
                        $sortFn = @$sortMap[$property];
                    }
                    if ($sortFn) {
                        usort($value, $sortFn);
                        $data[$property] = $value;
                    } else {
                        echo 'no sort for ' . $origin . '->' . $property . "\n";
                        die;
                    }
                }
                foreach ($value as $i => $element) {
                    if (is_object($element)) {
                        $data[$property][$i] = $this->sorted($element, $origin . '->' . $property . '[' . $i . ']');
                    }
                }
            }
        }
        return (object) $data;
    }
}
