<?php

namespace spec\Gaufrette\Adapter;

use org\bovigo\vfs\vfsStream;
use PhpSpec\ObjectBehavior;

class LocalSpec extends ObjectBehavior
{
    function let()
    {
        vfsStream::setup('test');
        vfsStream::copyFromFileSystem(__DIR__.'/MockFilesystem');
        $this->beConstructedWith(vfsStream::url('test'));
    }

    function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    function it_is_checksum_calculator()
    {
        $this->shouldHaveType('Gaufrette\Adapter\ChecksumCalculator');
    }

    function it_is_a_mime_type_provider()
    {
        $this->shouldHaveType('Gaufrette\Adapter\MimeTypeProvider');
    }

    function it_gets_the_file_mime_type()
    {
        $this->mimeType('filename')->shouldReturn('text/plain');
    }

    function it_is_stream_factory()
    {
        $this->shouldHaveType('Gaufrette\Adapter\StreamFactory');
    }

    function it_reads_file()
    {
        $this->read('filename')->shouldReturn("content\n");
    }

    function it_writes_file()
    {
        $this->write('filename', 'some content')->shouldReturn(12);
    }

    function it_renames_file()
    {
        $this->rename('filename', 'aaa/filename2')->shouldReturn(true);
    }

    function it_checks_if_file_exists()
    {
        $this->exists('filename')->shouldReturn(true);
        $this->exists('filename1')->shouldReturn(false);
    }

    function it_fetches_keys()
    {
        $expectedKeys = array('filename', 'dir', 'dir/file');
        sort($expectedKeys);
        $this->keys()->shouldReturn($expectedKeys);
    }

    function it_fetches_mtime()
    {
        $mtime = filemtime(vfsStream::url('test/filename'));
        $this->mtime('filename')->shouldReturn($mtime);
    }

    function it_deletes_file()
    {
        $this->delete('filename')->shouldReturn(true);
        $this->delete('filename1')->shouldReturn(false);
    }

    function it_checks_if_given_key_is_directory()
    {
        $this->isDirectory('dir')->shouldReturn(true);
        $this->isDirectory('filename')->shouldReturn(false);
    }

    function it_creates_local_stream()
    {
        $this->createStream('filename')->shouldReturnAnInstanceOf('Gaufrette\Stream\Local');
    }

    function it_does_not_allow_to_read_path_above_main_file_directory()
    {
        $this
            ->shouldThrow(new \OutOfBoundsException(sprintf('The path "%s" is out of the filesystem.', vfsStream::url('filename'))))
            ->duringRead('../filename')
        ;
        $this
            ->shouldThrow(new \OutOfBoundsException(sprintf('The path "%s" is out of the filesystem.', vfsStream::url('filename'))))
            ->duringExists('../filename')
        ;
    }

    function it_fails_when_directory_does_not_exists()
    {
        $this->beConstructedWith(vfsStream::url('other'));

        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringRead('filename')
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringWrite('filename', 'some content')
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringRename('filename', 'otherFilename')
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringExists('filename')
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringKeys()
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringMtime('filename')
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringDelete('filename')
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringIsDirectory('filename')
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringCreateStream('filename')
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringChecksum('filename')
        ;
        $this
            ->shouldThrow(new \RuntimeException(sprintf('The directory "%s" does not exist.', vfsStream::url('other'))))
            ->duringMimeType('filename')
        ;
    }

    function it_creates_directory_when_does_not_exists()
    {
        $this->beConstructedWith(vfsStream::url('test/other'), true);

        $this->exists('/')->shouldReturn(true);
    }
}
