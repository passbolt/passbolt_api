<?php

namespace Gaufrette\Functional\Adapter;

use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local;

class LocalTest extends FunctionalTestCase
{
    private $directory;

    public function setUp()
    {
        $this->directory = sprintf('%s/filesystem', str_replace('\\', '/', __DIR__));

        if (!file_exists($this->directory)) {
            mkdir($this->directory);
        }

        $this->filesystem = new Filesystem(new Local($this->directory));
    }

    public function tearDown()
    {
        $this->filesystem = null;

        if (file_exists($this->directory)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $this->directory,
                    \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS
                )
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

    /**
     * @test
     * @group functional
     */
    public function shouldWorkWithSyslink()
    {
        $dirname = sprintf(
            '%s/adapters/aaa',
            dirname(__DIR__)
        );
        $linkname = sprintf(
            '%s/adapters/../../../../link',
            dirname(__DIR__)
        );

        @mkdir($dirname);
        @unlink($linkname);
        symlink($dirname, $linkname);

        $this->filesystem = new Filesystem(new Local($linkname));
        $this->filesystem->write('test.txt', 'abc 123');

        $this->assertSame('abc 123', $this->filesystem->read('test.txt'));
        $this->filesystem->delete('test.txt');
        @unlink($linkname);
        @rmdir($dirname);
    }

    /**
     * @test
     * @covers Gaufrette\Adapter\Local
     */
    public function shouldListingOnlyGivenDirectory()
    {
        $dirname = sprintf(
            '%s/localDir',
            $this->directory
        );
        @mkdir($dirname);

        $this->filesystem = new Filesystem(new Local($this->directory));
        $this->filesystem->write('aaa.txt', 'some content');
        $this->filesystem->write('localDir/test.txt', 'some content');

        $dirs = $this->filesystem->listKeys('localDir/test');
        $this->assertEmpty($dirs['dirs']);
        $this->assertCount(1, $dirs['keys']);
        $this->assertEquals('localDir/test.txt', $dirs['keys'][0]);

        $dirs = $this->filesystem->listKeys();

        $this->assertCount(1, $dirs['dirs']);
        $this->assertEquals('localDir', $dirs['dirs'][0]);
        $this->assertCount(2, $dirs['keys']);
        $this->assertEquals('aaa.txt', $dirs['keys'][0]);
        $this->assertEquals('localDir/test.txt', $dirs['keys'][1]);

        @unlink($dirname.DIRECTORY_SEPARATOR.'test.txt');
        @unlink($this->directory.DIRECTORY_SEPARATOR.'aaa.txt');
        @rmdir($dirname);
    }
}
