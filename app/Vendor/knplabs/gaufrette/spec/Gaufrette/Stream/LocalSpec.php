<?php

namespace spec\Gaufrette\Stream;

use PhpSpec\ObjectBehavior;
use Gaufrette\StreamMode;
use org\bovigo\vfs\vfsStream;

class LocalSpec extends ObjectBehavior
{
    function it_throws_runtime_exception_when_file_doesnt_exists()
    {
        $this->beConstructedWith(vfsStream::url('other'));
        $this->shouldThrow('\RuntimeException')->duringOpen(new StreamMode('r'));
    }

    function it_throws_runtime_exception_when_file_doesnt_exists_and_custom_error_handler_specified()
    {
        $custom_error_handler = function ($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        };
        set_error_handler($custom_error_handler);

        $this->beConstructedWith(vfsStream::url('other'));
        $this->shouldThrow('\RuntimeException')->duringOpen(new StreamMode('r'));

        restore_error_handler();
    }
}
