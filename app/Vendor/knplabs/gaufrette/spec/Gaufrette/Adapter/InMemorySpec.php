<?php

namespace spec\Gaufrette\Adapter;

use PhpSpec\ObjectBehavior;

class InMemorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(array(
            'filename'  => array('mtime' => 12345, 'content' => 'content'),
            'filename2' => 'other content'
        ));
    }

    function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    function it_reads_file()
    {
        $this->read('filename')->shouldReturn('content');
    }

    function it_writes_file()
    {
        $this->write('filename', 'some content')->shouldReturn(12);
    }

    function it_renames_file()
    {
         $this->rename('filename', 'aaa/filename2')->shouldReturn(true);
         $this->exists('filename')->shouldReturn(false);
         $this->exists('aaa/filename2')->shouldReturn(true);
    }

    function it_checks_if_file_exists()
    {
        $this->exists('filename')->shouldReturn(true);
        $this->exists('filenameTest')->shouldReturn(false);
    }

    function it_fetches_keys()
    {
        $this->keys()->shouldReturn(array('filename', 'filename2'));
    }

    function it_fetches_mtime()
    {
        $this->mtime('filename')->shouldReturn(12345);
    }

    function it_deletes_file()
    {
        $this->delete('filename')->shouldReturn(true);
        $this->exists('filename')->shouldReturn(false);
    }

    function it_does_not_handle_dirs()
    {
        $this->isDirectory('filename')->shouldReturn(false);
        $this->isDirectory('filename2')->shouldReturn(false);
    }
}
