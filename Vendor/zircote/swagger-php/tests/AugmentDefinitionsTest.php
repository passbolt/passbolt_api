<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Processors\AugmentDefinitions;
use Swagger\Processors\MergeIntoSwagger;
use Swagger\StaticAnalyser;

class AugmentDefinitionsTest extends SwaggerTestCase
{

    public function testAugmentDefinitions()
    {
        $analyser = new StaticAnalyser();
        $analysis = $analyser->fromFile(__DIR__ . '/Fixtures/Customer.php');
        $analysis->process(new MergeIntoSwagger());
        $this->assertCount(1, $analysis->swagger->definitions);
        $customer = $analysis->swagger->definitions[0];
        $this->assertNull($customer->properties, 'Sanity check. @SWG\Property\'s not yet merged ');
        $analysis->process(new AugmentDefinitions());
        $this->assertSame('Customer', $customer->definition, '@SWG\Definition()->definition based on classname');
        $this->assertCount(5, $customer->properties, '@SWG\Property()s are merged into the @SWG\Definition of the class');
    }
}
