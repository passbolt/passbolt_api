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
     * @group functional
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

    /**
     * @test
     * @covers Gaufrette\Adapter\Local
     * @group functional
     */
    public function shouldListingAllKeys()
    {
        $dirname = sprintf(
            '%s/localDir',
            $this->directory
        );
        @mkdir($dirname);

        $this->filesystem = new Filesystem(new Local($this->directory));
        $this->filesystem->write('aaa.txt', 'some content');
        $this->filesystem->write('localDir/dir1/dir2/dir3/test.txt', 'some content');

        $keys = $this->filesystem->keys();
        $dirs = $this->filesystem->listKeys();
        $this->assertCount(6, $keys);
        $this->assertCount(4, $dirs['dirs']);
        $this->assertEquals('localDir/dir1/dir2/dir3/test.txt', $dirs['keys'][1]);

        foreach ($dirs['keys'] as $item) {
            @unlink($item);
        }
        $reversed = array_reverse($dirs['dirs']);
        foreach ($reversed as $item) {
            @rmdir($item);
        }
    }

    /**
     * @test
     * @group functional
     */
    public function shouldBeAbleToClearCache()
    {
        $dirname = sprintf(
            '%s/adapters/bbb',
            dirname(__DIR__)
        );

        @mkdir($dirname);

        $this->filesystem = new Filesystem(new Local($dirname));

        $this->filesystem->get('test.txt', true);
        $this->filesystem->write('test.txt', '123', true);

        $this->filesystem->get('test2.txt', true);
        $this->filesystem->write('test2.txt', '123', true);

        $fsReflection = new \ReflectionClass($this->filesystem);

        $fsIsFileInRegister = $fsReflection->getMethod('isFileInRegister');
        $fsIsFileInRegister->setAccessible(true);

        $this->assertTrue($fsIsFileInRegister->invoke($this->filesystem, 'test.txt'));
        $this->filesystem->removeFromRegister('test.txt');
        $this->assertFalse($fsIsFileInRegister->invoke($this->filesystem, 'test.txt'));

        $this->filesystem->clearFileRegister();
        $fsRegister = $fsReflection->getProperty('fileRegister');
        $fsRegister->setAccessible(true);
        $this->assertEquals(0, count($fsRegister->getValue($this->filesystem)));

        $this->filesystem->delete('test.txt');
        $this->filesystem->delete('test2.txt');
        @rmdir($dirname);
    }
}
