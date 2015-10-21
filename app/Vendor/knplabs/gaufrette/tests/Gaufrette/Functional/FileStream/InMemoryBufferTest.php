<?php

namespace Gaufrette\Functional\FileStream;

use Gaufrette\Filesystem;
use Gaufrette\Adapter\InMemory as InMemoryAdapter;

class InMemoryBufferTest extends FunctionalTestCase
{
    public function setUp()
    {
        $this->filesystem = new Filesystem(new InMemoryAdapter(array()));

        $this->registerLocalFilesystemInStream();
    }
}
