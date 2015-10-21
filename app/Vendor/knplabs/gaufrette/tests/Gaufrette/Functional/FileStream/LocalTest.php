<?php

namespace Gaufrette\Functional\FileStream;

use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local as LocalAdapter;

class LocalTest extends FunctionalTestCase
{
    protected $directory;

    public function setUp()
    {
        $this->directory = __DIR__.DIRECTORY_SEPARATOR.'filesystem';
        @mkdir($this->directory);
        @chmod($this->directory, 0777);
        $this->filesystem = new Filesystem(new LocalAdapter($this->directory, true));

        $this->registerLocalFilesystemInStream();
    }

    public function tearDown()
    {
        if (is_file($file = $this->directory.DIRECTORY_SEPARATOR.'test.txt')) {
            @unlink($file);
        }
        if (is_dir($this->directory)) {
            @rmdir($this->directory);
        }
    }
}
