<?php

namespace spec\Gaufrette\Adapter;

use org\bovigo\vfs\vfsStream;
use PhpSpec\ObjectBehavior;

class SafeLocalSpec extends ObjectBehavior
{
    function let()
    {
        vfsStream::setup('test');
        vfsStream::copyFromFileSystem(__DIR__.'/MockFilesystem');
        $this->beConstructedWith(vfsStream::url('test'));
    }

    function it_is_local_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter\Local');
    }

    function it_computes_path_using_base64()
    {
        rename(vfsStream::url('test/filename'), vfsStream::url('test/'.base64_encode('filename')));
        $this->read('filename')->shouldReturn("content\n");
    }

    function it_computes_key_back_using_base64()
    {
        $this->keys()->shouldReturn(array(base64_decode('dir/file'), base64_decode('filename')));
    }
}
