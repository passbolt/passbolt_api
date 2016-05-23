<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Analyser;
use Swagger\Context;
use Swagger\StaticAnalyser;

class StaticAnalyserTest extends SwaggerTestCase
{
    public function testWrongCommentType()
    {
        $analyser = new StaticAnalyser();
        $this->assertSwaggerLogEntryStartsWith('Annotations are only parsed inside `/**` DocBlocks');
        $analyser->fromCode("<?php\n/*\n * @SWG\Parameter() */", new Context([]));
    }

    public function testIndentationCorrection()
    {
        $analyser = new StaticAnalyser();
        $analysis = $analyser->fromFile(__DIR__ . '/Fixtures/routes.php');
        $this->assertCount(16, $analysis->annotations);
    }
    
    public function testTrait()
    {
        $analyser = new StaticAnalyser();
        $analysis = $analyser->fromFile(__DIR__ . '/Fixtures/HelloTrait.php');
        $this->assertCount(2, $analysis->annotations);
        $property = $analysis->getAnnotationsOfType('Swagger\Annotations\Property')[0];
        $this->assertSame('Hello', $property->_context->trait);
    }
    
    public function testThirdPartyAnnotations()
    {
        $backup = Analyser::$whitelist;
        Analyser::$whitelist = ['Swagger\Annotations\\'];
        $analyser = new StaticAnalyser();
        $defaultAnalysis = $analyser->fromFile(__DIR__ . '/Fixtures/ThirdPartyAnnotations.php');
        $this->assertCount(3, $defaultAnalysis->annotations, 'Only read the @SWG annotations, skip the others.');
        // Allow Swagger to parse 3rd party annotations
        // might contain useful info that could be extracted with a custom processor
        Analyser::$whitelist[] = 'Zend\Form\Annotation';
        $swagger = \Swagger\scan(__DIR__ . '/Fixtures/ThirdPartyAnnotations.php');
        $this->assertSame('api/3rd-party', $swagger->paths[0]->path);
        $this->assertCount(10, $swagger->_unmerged);
        Analyser::$whitelist = $backup;
        $analysis = $swagger->_analysis;
        $annotations = $analysis->getAnnotationsOfType('Zend\Form\Annotation\Name');
        $this->assertCount(1, $annotations);
        $context = $analysis->getContext($annotations[0]);
        $this->assertInstanceOf('Swagger\Context', $context);
        $this->assertSame('ThirdPartyAnnotations', $context->class);
        $this->assertSame('\SwaggerFixtures\ThirdPartyAnnotations', $context->fullyQualifiedName($context->class));
        $this->assertCount(2, $context->annotations);
    }
}
