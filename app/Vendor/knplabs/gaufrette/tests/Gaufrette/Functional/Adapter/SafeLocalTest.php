<?php

namespace Gaufrette\Functional\Adapter;

use Gaufrette\Filesystem;
use Gaufrette\Adapter\SafeLocal;

class SafeLocalTest extends FunctionalTestCase
{
    public function setUp()
    {
        if (!file_exists($this->getDirectory())) {
            mkdir($this->getDirectory());
        }

        $this->filesystem = new Filesystem(new SafeLocal($this->getDirectory()));
    }

    public function tearDown()
    {
        $this->filesystem = null;

        if (file_exists($this->getDirectory())) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $this->getDirectory(),
                    \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS
                ),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($iterator as $item) {
                if ($item->isDir()) {
                    rmdir(strval($item));
                } else {
                    unlink(strval($item));
                }
            }
        }
    }

    private function getDirectory()
    {
        return sprintf('%s/filesystem', __DIR__);
    }
}
