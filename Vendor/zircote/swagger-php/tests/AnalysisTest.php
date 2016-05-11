<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Analysis;
use Swagger\StaticAnalyser;

class AnalysisTest extends SwaggerTestCase
{

    public function testRegisterProcessor()
    {
        $counter = 0;
        $analysis = new Analysis();
        $analysis->process();
        $this->assertSame(0, $counter);
        $countProcessor = function (Analysis $a) use (&$counter) {
            $counter++;
        };
        Analysis::registerProcessor($countProcessor);
        $analysis->process();
        $this->assertSame(1, $counter);
        Analysis::unregisterProcessor($countProcessor);
        $analysis->process();
        $this->assertSame(1, $counter);
    }
    
    public function testGetSubclasses()
    {
        $analyser = new StaticAnalyser();
        $analysis = $analyser->fromFile(__DIR__ . '/Fixtures/Child.php');
        $analysis->addAnalysis($analyser->fromFile(__DIR__ . '/Fixtures/GrandParent.php'));
        $analysis->addAnalysis($analyser->fromFile(__DIR__ . '/Fixtures/Parent.php'));
        
        $this->assertCount(3, $analysis->classes, '3 classes should\'ve been detected');
        
        $subclasses = $analysis->getSubClasses('\SwaggerFixtures\GrandParent');
        $this->assertCount(2, $subclasses, 'GrandParent has 2 subclasses');
        $this->assertSame(['\SwaggerFixtures\Parent', '\AnotherNamespace\Child'], array_keys($subclasses));
        $this->assertSame(['\AnotherNamespace\Child'], array_keys($analysis->getSubClasses('\SwaggerFixtures\Parent')));
    }
    
    public function testGetParentClasses()
    {
        $analyser = new StaticAnalyser();
        $analysis = $analyser->fromFile(__DIR__ . '/Fixtures/Child.php');
        $analysis->addAnalysis($analyser->fromFile(__DIR__ . '/Fixtures/GrandParent.php'));
        $analysis->addAnalysis($analyser->fromFile(__DIR__ . '/Fixtures/Parent.php'));
        
        $this->assertCount(3, $analysis->classes, '3 classes should\'ve been detected');
        
        $superclasses = $analysis->getSuperClasses('\AnotherNamespace\Child');
        $this->assertCount(2, $superclasses, 'Child has a chain of 2 super classes');
        $this->assertSame(['\SwaggerFixtures\Parent', '\SwaggerFixtures\GrandParent'], array_keys($superclasses));
        $this->assertSame(['\SwaggerFixtures\GrandParent'], array_keys($analysis->getSuperClasses('\SwaggerFixtures\Parent')));
    }
}
