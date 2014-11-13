<?php

namespace spec\Gaufrette\Adapter;

//hack - mock php built-in functions
require_once 'functions.php';

use PhpSpec\ObjectBehavior;

class ApcSpec extends ObjectBehavior
{
    function let()
    {
        global $extensionLoaded;
        $extensionLoaded = true;

        $this->beConstructedWith('prefix-apc-test/');
    }

    function letgo()
    {
        global $extensionLoaded;
        $extensionLoaded = null;
    }

    function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    function it_reads_file()
    {
        $this->read('filename')->shouldReturn('prefix-apc-test/filename content');
        $this->read('filename2')->shouldReturn('prefix-apc-test/filename2 content');
    }

    function it_writes_file()
    {
        $this->write('filename', 'some content')->shouldReturn(12);
        $this->write('invalid', 'some content')->shouldReturn(false);
    }

    function it_deletes_file()
    {
        $this->delete('filename')->shouldReturn(true);
        $this->delete('invalid')->shouldReturn(false);
    }

    function it_rename_file()
    {
        $this->rename('filename', 'aaa/filename2')->shouldReturn(true);
        $this->rename('filename', 'invalid')->shouldReturn(false);
        $this->rename('invalid', 'somename')->shouldReturn(false);
    }

    function it_checks_if_file_exists()
    {
        $this->exists('filename')->shouldReturn(true);
        $this->exists('invalid')->shouldReturn(false);
    }

    function it_does_not_handles_directory()
    {
        $this->isDirectory('filename')->shouldReturn(false);
        $this->isDirectory('invalid')->shouldReturn(false);
    }
}
