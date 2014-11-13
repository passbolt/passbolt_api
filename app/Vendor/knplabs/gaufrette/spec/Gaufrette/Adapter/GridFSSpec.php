<?php

namespace spec\Gaufrette\Adapter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GridFSSpec extends ObjectBehavior
{
    /**
     * @param \MongoGridFS $gridFs
     */
    function let($gridFs)
    {
        $this->beConstructedWith($gridFs);
    }

    function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    function it_is_checksum_calculator()
    {
        $this->shouldHaveType('Gaufrette\Adapter\ChecksumCalculator');
    }

    function it_supports_metadata()
    {
        $this->shouldHaveType('Gaufrette\Adapter\MetadataSupporter');
    }

    function it_supports_native_list_keys()
    {
        $this->shouldHaveType('Gaufrette\Adapter\ListKeysAware');
    }

    /**
     * @param \MongoGridFS $gridFs
     * @param \MongoGridFSFile $file
     */
    function it_reads_file($gridFs, $file)
    {
        $file
            ->getBytes()
            ->willReturn('some content')
        ;
        $gridFs
            ->findOne('filename', array())
            ->shouldBeCalled()
            ->willReturn($file)
        ;

        $this->read('filename')->shouldReturn('some content');
    }

    /**
     * @param \MongoGridFS $gridFs
     */
    function it_does_not_fail_when_cannot_read($gridFs)
    {
        $gridFs
            ->findOne('filename', array())
            ->shouldBeCalled()
            ->willReturn(null)
        ;

        $this->read('filename')->shouldReturn(false);
    }

    /**
     * @param \MongoGridFS $gridFs
     * @param \MongoGridFSFile $file
     */
    function it_checks_if_file_exists($gridFs, $file)
    {
        $gridFs
            ->findOne('filename', array())
            ->willReturn($file)
        ;
        $gridFs
            ->findOne('filename2', array())
            ->willReturn(null)
        ;

        $this->exists('filename')->shouldReturn(true);
        $this->exists('filename2')->shouldReturn(false);
    }

    /**
     * @param \MongoGridFS $gridFs
     */
    function it_deletes_file($gridFs)
    {
        $file = new \stdClass;
        $file->file = array('_id' => 123);
        $gridFs
            ->findOne('filename', array('_id'))
            ->willReturn($file)
        ;
        $gridFs
            ->delete(123)
            ->willReturn(true)
        ;

        $this->delete('filename')->shouldReturn(true);
    }

    /**
     * @param \MongoGridFS $gridFs
     */
    function it_does_not_delete_file($gridFs)
    {
        $file = new \stdClass;
        $file->file = array('_id' => 123);
        $gridFs
            ->findOne('filename', array('_id'))
            ->willReturn($file)
        ;
        $gridFs
            ->delete(123)
            ->willReturn(false)
        ;
        $this->delete('filename')->shouldReturn(false);

        $gridFs
            ->findOne('filename', array('_id'))
            ->willReturn(null)
        ;
        $this->delete('filename')->shouldReturn(false);
    }

    /**
     * @param \MongoGridFS $gridFs
     * @param \MongoGridFSFile $file
     */
    function it_writes_file($gridFs, $file)
    {
        $file
            ->getSize()
            ->willReturn(12)
        ;
        $gridFs
            ->findOne('filename', array())
            ->willReturn(null)
        ;
        $gridFs
            ->storeBytes('some content', array('date' => 1234, 'someother' => 'metadata', 'filename' => 'filename'))
            ->willReturn('someId')
        ;
        $gridFs
            ->findOne(array('_id' => 'someId'))
            ->willReturn($file)
        ;

        $this->setMetadata('filename', array('date' => 1234, 'someother' => 'metadata'));
        $this
            ->write('filename', 'some content')
            ->shouldReturn(12)
        ;
    }

    /**
     * @param \MongoGridFS $gridFs
     * @param \MongoGridFSFile $file
     */
    function it_updates_file($gridFs, $file)
    {
        $someFile = new \stdClass;
        $someFile->file = array('_id' => 123);
        $gridFs
            ->findOne('filename', array('_id'))
            ->shouldBeCalled()
            ->willReturn($someFile)
        ;
        $gridFs
            ->delete(123)
            ->shouldBeCalled()
            ->willReturn(true)
        ;

        $file
            ->getSize()
            ->willReturn(12)
        ;
        $gridFs
            ->findOne('filename', array())
            ->willReturn($file)
        ;
        $gridFs
            ->storeBytes(Argument::any(), Argument::any())
            ->willReturn('someId')
        ;
        $gridFs
            ->findOne(array('_id' => 'someId'))
            ->willReturn($file)
        ;

        $this
            ->write('filename', 'some content')
            ->shouldReturn(12)
        ;
    }

    /**
     * @param \MongoGridFS $gridFs
     * @param \MongoGridFSFile $file
     */
    function it_renames_file($gridFs, $file)
    {
        $file
            ->getBytes()
            ->willReturn('some content')
        ;
        $file
            ->getSize()
            ->willReturn(12)
        ;
        $gridFs
            ->findOne('otherFilename', array())
            ->willReturn(null)
        ;
        $gridFs
            ->findOne('filename', array())
            ->shouldBeCalled()
            ->willReturn($file)
        ;
        $gridFs
            ->storeBytes('some content', array('date' => 1234, 'filename' => 'otherFilename'))
            ->shouldBeCalled()
            ->willReturn('someId')
        ;

        $fileToDelete = new \stdClass;
        $fileToDelete->file = array('_id' => 123);
        $gridFs
            ->findOne('filename', array('_id'))
            ->willReturn($fileToDelete)
        ;
        $gridFs
            ->findOne(array('_id' => 'someId'))
            ->willReturn($file)
        ;
        $gridFs
            ->delete(123)
            ->shouldBeCalled()
            ->willReturn(true)
        ;

        $this->setMetadata('otherFilename', array('date' => 1234));
        $this->rename('filename', 'otherFilename')->shouldReturn(true);
    }

    /**
     * @param \MongoGridFS $gridFs
     */
    function it_fetches_keys($gridFs, $file, $otherFile)
    {
        $gridFs
            ->find(array(), array('filename'))
            ->willReturn(array(new TestFile('filename'), new TestFile('otherFilename')))
        ;

        $this->keys()->shouldReturn(array('filename', 'otherFilename'));
    }

    /**
     * @param \MongoGridFS $gridFs
     */
    function it_fetches_mtime($gridFs)
    {
        $time = new \stdClass;
        $time->sec = 12345;

        $someFile = new \stdClass;
        $someFile->file = array('date' => $time);
        $gridFs
            ->findOne('filename', array('date'))
            ->willReturn($someFile)
        ;

        $this->mtime('filename')->shouldReturn(12345);
    }

    /**
     * @param \MongoGridFS $gridFs
     */
    function it_calculates_checksum($gridFs)
    {
        $someFile = new \stdClass;
        $someFile->file = array('md5' => 'md5123');
        $gridFs
            ->findOne('filename', array('md5'))
            ->willReturn($someFile)
        ;

        $this->checksum('filename')->shouldReturn('md5123');
    }
}

class TestFile
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }
}
