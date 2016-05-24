<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Context;

class ContextTest extends SwaggerTestCase
{

    public function testDetect()
    {
        $context = Context::detect();
        $line = __LINE__ - 1;
        $this->assertSame('ContextTest', $context->class);
        $this->assertSame('\SwaggerTests\ContextTest', $context->fullyQualifiedName($context->class));
        $this->assertSame('testDetect', $context->method);
        $this->assertSame(__FILE__, $context->filename);
        $this->assertSame($line, $context->line);
        $this->assertSame('SwaggerTests', $context->namespace);
//        $this->assertCount(1, $context->uses); // Context::detect() doesn't pick up USE statements (yet)
    }

    public function testFullyQualifiedName()
    {
        $swagger = \Swagger\scan(__DIR__ . '/Fixtures/Customer.php');
        $context = $swagger->definitions[0]->_context;
        // resolve with namespace
        $this->assertSame('\FullyQualified', $context->fullyQualifiedName('\FullyQualified'));
        $this->assertSame('\SwaggerFixures\Unqualified', $context->fullyQualifiedName('Unqualified'));
        $this->assertSame('\SwaggerFixures\Namespace\Qualified', $context->fullyQualifiedName('Namespace\Qualified'));
        // respect use statements
        $this->assertSame('\Exception', $context->fullyQualifiedName('Exception'));
        $this->assertSame('\SwaggerFixures\Customer', $context->fullyQualifiedName('Customer'));
        $this->assertSame('\Swagger\Logger', $context->fullyQualifiedName('Logger'));
        $this->assertSame('\Swagger\Logger', $context->fullyQualifiedName('lOgGeR')); // php has case-insensitive class names :-(
        $this->assertSame('\Swagger\Logger', $context->fullyQualifiedName('SwgLogger'));
        $this->assertSame('\Swagger\Annotations\QualifiedAlias', $context->fullyQualifiedName('SWG\QualifiedAlias'));
    }
}
