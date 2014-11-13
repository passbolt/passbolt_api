<?php

namespace Gaufrette\Functional\Adapter;

use Gaufrette\Adapter\Zip;
use Gaufrette\Filesystem;

class ZipTest extends FunctionalTestCase
{
    public function setUp()
    {
        if (!extension_loaded('zip')) {
            return $this->markTestSkipped('The zip extension is not available.');
        }

        @touch(__DIR__ . '/test.zip');

        $this->filesystem = new Filesystem(new Zip(__DIR__ . '/test.zip'));
    }

    public function tearDown()
    {
        parent::tearDown();

        @unlink(__DIR__ . '/test.zip');
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @group functional
     */
    public function shouldNotAcceptInvalidZipArchive()
    {
        new Zip(__FILE__);
    }

    /**
     * @test
     * @group functional
     */
    public function shouldCreateNewZipArchive()
    {
        $tmp = tempnam(sys_get_temp_dir(), uniqid());
        $za = new Zip($tmp);

        $this->assertFileExists($tmp);

        return $za;
    }
}
