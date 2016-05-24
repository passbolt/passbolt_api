<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Processors\AugmentDefinitions;
use Swagger\Processors\AugmentProperties;
use Swagger\Processors\MergeIntoSwagger;
use Swagger\StaticAnalyser;

class AugmentPropertiesTest extends SwaggerTestCase
{

    public function testAugmentProperties()
    {
        $analyser = new StaticAnalyser();
        $analysis = $analyser->fromFile(__DIR__ . '/Fixtures/Customer.php');
        $analysis->process(new MergeIntoSwagger());
        $this->assertCount(1, $analysis->swagger->definitions);
        $customer = $analysis->swagger->definitions[0];
        $this->assertSame(null, $customer->properties, 'Sanity check. @SWG\Property\'s not yet erged ');
        $analysis->process(new AugmentDefinitions());
        $analysis->process(new AugmentProperties());
        $firstname = $customer->properties[0];
        $this->assertSame('firstname', $firstname->property, '@SWG\Property()->property based on propertyname');
        $this->assertSame('The firstname of the customer.', $firstname->description, '@SWG\Property()->description based on @var description');
        $this->assertSame('string', $firstname->type, '@SWG\Property()->type based on @var declaration');
        $lastname = $customer->properties[1];
        $this->assertSame('lastname', $lastname->property);
        $this->assertSame('The lastname of the customer.', $lastname->description);
        $this->assertSame('string', $lastname->type);
        $tags = $customer->properties[2];
        $this->assertSame('tags', $tags->property);
        $this->assertSame('array', $tags->type, 'Detect array notation: @var string[]');
        $this->assertSame('string', $tags->items->type);
        $submittedBy = $customer->properties[3];
        $this->assertSame('submittedBy', $submittedBy->property);
        $this->assertSame('#/definitions/Customer', $submittedBy->ref);
        $friends = $customer->properties[4];
        $this->assertSame('friends', $friends->property);
        $this->assertSame('array', $friends->type);
        $this->assertSame('#/definitions/Customer', $friends->items->ref);
    }
}
