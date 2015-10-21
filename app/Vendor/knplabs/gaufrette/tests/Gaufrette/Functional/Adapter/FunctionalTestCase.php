<?php

namespace Gaufrette\Functional\Adapter;

use Gaufrette\Filesystem;

abstract class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function getAdapterName()
    {
        if (!preg_match('/\\\\(\w+)Test$/', get_class($this), $matches)) {
            throw new \RuntimeException(sprintf(
                'Unable to guess filesystem name from class "%s", '.
                'please override the ->getAdapterName() method.',
                get_class($this)
            ));
        }

        return $matches[1];
    }

    public function setUp()
    {
        $basename = $this->getAdapterName();
        $filename = sprintf(
            '%s/adapters/%s.php',
            dirname(__DIR__),
            $basename
        );

        if (!file_exists($filename)) {
            return $this->markTestSkipped(<<<EOF
To run the {$basename} filesystem tests, you must:

 1. Copy the file "{$filename}.dist" as "{$filename}"
 2. Modify the copied file to fit your environment
EOF
            );
        }

        $adapter = include $filename;
        $this->filesystem = new Filesystem($adapter);
    }

    public function tearDown()
    {
        if (null === $this->filesystem) {
            return;
        }

        $this->filesystem = null;
    }

    /**
     * @test
     * @group functional
     */
    public function shouldWriteAndRead()
    {
        $this->assertEquals(12, $this->filesystem->write('foo', 'Some content'));
        $this->assertEquals(13, $this->filesystem->write('test/subdir/foo', 'Some content1', true));

        $this->assertEquals('Some content', $this->filesystem->read('foo'));
        $this->assertEquals('Some content1', $this->filesystem->read('test/subdir/foo'));
        $this->filesystem->delete('foo');
        $this->filesystem->delete('test/subdir/foo');
    }

    /**
     * @test
     * @group functional
     */
    public function shouldUpdateFileContent()
    {
        $this->filesystem->write('foo', 'Some content');
        $this->filesystem->write('foo', 'Some content updated', true);

        $this->assertEquals('Some content updated', $this->filesystem->read('foo'));
        $this->filesystem->delete('foo');
    }

    /**
     * @test
     * @group functional
     */
    public function shouldCheckIfFileExists()
    {
        $this->assertFalse($this->filesystem->has('foo'));

        $this->filesystem->write('foo', 'Some content');

        $this->assertTrue($this->filesystem->has('foo'));
        $this->assertFalse($this->filesystem->has('test/somefile'));
        $this->assertFalse($this->filesystem->has('test/somefile'));

        $this->filesystem->delete('foo');
    }

    /**
     * @test
     * @group functional
     */
    public function shouldGetMtime()
    {
        $this->filesystem->write('foo', 'Some content');

        $this->assertGreaterThan(0, $this->filesystem->mtime('foo'));

        $this->filesystem->delete('foo');
    }

    /**
     * @test
     * @group functional
     * @expectedException \RuntimeException
     * @expectedMessage Could not get mtime for the "foo" key
     */
    public function shouldFailWhenTryMtimeForKeyWhichDoesNotExist()
    {
        $this->assertFalse($this->filesystem->mtime('foo'));
    }

    /**
     * @test
     * @group functional
     */
    public function shouldRenameFile()
    {
        $this->filesystem->write('foo', 'Some content');
        $this->filesystem->rename('foo', 'boo');

        $this->assertFalse($this->filesystem->has('foo'));
        $this->assertEquals('Some content', $this->filesystem->read('boo'));
        $this->filesystem->delete('boo');

        $this->filesystem->write('foo', 'Some content');
        $this->filesystem->rename('foo', 'somedir/sub/boo');

        $this->assertFalse($this->filesystem->has('somedir/sub/foo'));
        $this->assertEquals('Some content', $this->filesystem->read('somedir/sub/boo'));
        $this->filesystem->delete('somedir/sub/boo');
    }

    /**
     * @test
     * @group functional
     */
    public function shouldDeleteFile()
    {
        $this->filesystem->write('foo', 'Some content');

        $this->assertTrue($this->filesystem->has('foo'));

        $this->filesystem->delete('foo');

        $this->assertFalse($this->filesystem->has('foo'));
    }

    /**
     * @test
     * @group functional
     */
    public function shouldFetchKeys()
    {
        $this->assertEquals(array(), $this->filesystem->keys());

        $this->filesystem->write('foo', 'Some content');
        $this->filesystem->write('bar', 'Some content');
        $this->filesystem->write('baz', 'Some content');

        $actualKeys = $this->filesystem->keys();

        $this->assertEquals(3, count($actualKeys));
        foreach (array('foo', 'bar', 'baz') as $key) {
            $this->assertContains($key, $actualKeys);
        }

        $this->filesystem->delete('foo');
        $this->filesystem->delete('bar');
        $this->filesystem->delete('baz');
    }

    /**
     * @test
     * @group functional
     */
    public function shouldWorkWithHiddenFiles()
    {
        $this->filesystem->write('.foo', 'hidden');
        $this->assertTrue($this->filesystem->has('.foo'));
        $this->assertContains('.foo', $this->filesystem->keys());
        $this->filesystem->delete('.foo');
        $this->assertFalse($this->filesystem->has('.foo'));
    }
    
    /**
     * @test
     * @group functional
     */
    public function shouldKeepFileObjectInRegister()
    {
        $FileObjectA = $this->filesystem->createFile('somefile');        
        $FileObjectB = $this->filesystem->createFile('somefile');
        
        $this->assertTrue($FileObjectA === $FileObjectB);
    }
    
    /**
     * @test
     * @group functional
     */
    public function shouldWrtieToSameFile()
    {
        $FileObjectA = $this->filesystem->createFile('somefile');
        $FileObjectA->setContent('ABC');
        
        $FileObjectB = $this->filesystem->createFile('somefile');
        $FileObjectB->setContent('DEF');
        
        $this->assertEquals('DEF', $FileObjectB->getContent());
        
        $this->filesystem->delete('somefile');
    }
}
