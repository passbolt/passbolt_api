<?php

/**
 * @license Apache 2.0
 */

namespace SwaggerTests;

use Swagger\Util;

class UtilTest extends SwaggerTestCase
{

    public function testExclude()
    {
        $swagger = \Swagger\scan(__DIR__ . '/Fixtures', ['exclude' => ['Customer.php', 'UsingRefs.php', 'GrandParent.php']]);
        $this->assertSame('Fixture for ParserTest', $swagger->info->title, 'No errors about duplicate @SWG\Info() annotations');
    }
}
