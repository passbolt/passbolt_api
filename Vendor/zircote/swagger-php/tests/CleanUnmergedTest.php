<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Processors\MergeIntoSwagger;
use Swagger\Processors\CleanUnmerged;
use Swagger\Analysis;
use Exception;

class CleanUnmergedTest extends SwaggerTestCase
{

    public function testCleanUnmergedProcessor()
    {
        $comment = <<<END
@SWG\Info(
    title="Info only has one contact field.",
    version="test",
)
@SWG\License(
    name="MIT",
    @SWG\Contact(
        name="Batman"
    )
)

END;
        $analysis = new Analysis($this->parseComment($comment));
        $this->assertCount(3, $analysis->annotations);
        $analysis->process(new MergeIntoSwagger());
        $this->assertCount(4, $analysis->annotations);
        $before = $analysis->split();
        $this->assertCount(2, $before->merged->annotations, 'Generated @SWG\Swagger + @SWG\Info');
        $this->assertCount(2, $before->unmerged->annotations, '@SWG\License + @SWG\Contact');
        $this->assertCount(0, $analysis->swagger->_unmerged);
        $analysis->validate(); // Validation fails to detect the unmerged annotations.

        // CleanUnmerged should place the unmerged annotions into the swagger->_unmerged array.
        $analysis->process(new CleanUnmerged());
        $between = $analysis->split();
        $this->assertCount(2, $between->merged->annotations, 'Generated @SWG\Swagger + @SWG\Info');
        $this->assertCount(2, $between->unmerged->annotations, '@SWG\License + @SWG\Contact');
        $this->assertCount(2, $analysis->swagger->_unmerged); // 1 would also be oke, Could a'Only the @SWG\License'
        $this->assertSwaggerLogEntryStartsWith('Unexpected @SWG\License(), expected to be inside @SWG\Info in ');
        $this->assertSwaggerLogEntryStartsWith('Unexpected @SWG\Contact(), expected to be inside @SWG\Info in ');
        $analysis->validate();

        // When a processor places a previously unmerged annotation into the swagger obect.
        $license = $analysis->getAnnotationsOfType('Swagger\Annotations\License')[0];
        $contact = $analysis->getAnnotationsOfType('Swagger\Annotations\Contact')[0];
        $analysis->swagger->info->contact = $contact;
        $this->assertCount(1, $license->_unmerged);
        $analysis->process(new CleanUnmerged());
        $this->assertCount(0, $license->_unmerged);
        $after = $analysis->split();
        $this->assertCount(3, $after->merged->annotations, 'Generated @SWG\Swagger + @SWG\Info + @SWG\Contact');
        $this->assertCount(1, $after->unmerged->annotations, '@SWG\License');
        $this->assertCount(1, $analysis->swagger->_unmerged);
        $this->assertSwaggerLogEntryStartsWith('Unexpected @SWG\License(), expected to be inside @SWG\Info in ');
        $analysis->validate();
    }
}
