<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

class AbstractAnnotationTest extends SwaggerTestCase
{

    public function testVendorFields()
    {
        $annotations = $this->parseComment('@SWG\Get(x={"internal-id": 123})');
        $output = $annotations[0]->jsonSerialize();
        $prefixedProperty = 'x-internal-id';
        $this->assertSame(123, $output->$prefixedProperty);
    }

    public function testInvalidField()
    {
        $this->assertSwaggerLogEntryStartsWith('Unexpected field "doesnot" for @SWG\Get(), expecting');
        $this->parseComment('@SWG\Get(doesnot="exist")');
    }

    public function testUmergedAnnotation()
    {
        $swagger = $this->createSwaggerWithInfo();
        $swagger->merge($this->parseComment('@SWG\Items()'));
        $this->assertSwaggerLogEntryStartsWith('Unexpected @SWG\Items(), expected to be inside @SWG\\');
        $swagger->validate();
    }

    public function testConflictedNesting()
    {
        $comment = <<<END
@SWG\Info(
    title="Info only has one contact field..",
    version="test",
    @SWG\Contact(name="first"),
    @SWG\Contact(name="second")
)
END;
        $annotations = $this->parseComment($comment);
        $this->assertSwaggerLogEntryStartsWith('Only one @SWG\Contact() allowed for @SWG\Info() multiple found in:');
        $annotations[0]->validate();
    }

    public function testKey()
    {
        $comment = <<<END
@SWG\Response(
    @SWG\Header(header="X-CSRF-Token",description="Token to prevent Cross Site Request Forgery")
)
END;
        $annotations = $this->parseComment($comment);
        $this->assertEquals('{"headers":{"X-CSRF-Token":{"description":"Token to prevent Cross Site Request Forgery"}}}', json_encode($annotations[0]));
    }

    public function testConflictingKey()
    {
        $comment = <<<END
@SWG\Response(
    description="The headers in response must have unique header values",
    @SWG\Header(header="X-CSRF-Token", type="string", description="first"),
    @SWG\Header(header="X-CSRF-Token", type="string", description="second")
)
END;
        $annotations = $this->parseComment($comment);
        $this->assertSwaggerLogEntryStartsWith('Multiple @SWG\Header() with the same header="X-CSRF-Token":');
        $annotations[0]->validate();
    }

    public function testRequiredFields()
    {
        $annotations = $this->parseComment('@SWG\Info()');
        $info = $annotations[0];
        $this->assertSwaggerLogEntryStartsWith('Missing required field "title" for @SWG\Info() in ');
        $this->assertSwaggerLogEntryStartsWith('Missing required field "version" for @SWG\Info() in ');
        $info->validate();
    }

    public function testTypeValidation()
    {
        $comment = <<<END
@SWG\Parameter(
    name=123,
    type="strig",
    in="dunno",
    required="maybe",
    maximum="twentytwo"
)
END;
        $annotations = $this->parseComment($comment);
        $parameter = $annotations[0];
        $this->assertSwaggerLogEntryStartsWith('@SWG\Parameter(name=123,in="dunno")->name is a "integer", expecting a "string" in ');
        $this->assertSwaggerLogEntryStartsWith('@SWG\Parameter(name=123,in="dunno")->in "dunno" is invalid, expecting "query", "header", "path", "formData", "body" in ');
        $this->assertSwaggerLogEntryStartsWith('@SWG\Parameter(name=123,in="dunno")->required is a "string", expecting a "boolean" in ');
        $this->assertSwaggerLogEntryStartsWith('@SWG\Parameter(name=123,in="dunno")->maximum is a "string", expecting a "number" in ');
        $this->assertSwaggerLogEntryStartsWith('@SWG\Parameter(name=123,in="dunno")->type must be "string", "number", "integer", "boolean", "array", "file" when @SWG\Parameter()->in != "body" in ');
        $parameter->validate();
    }
}
