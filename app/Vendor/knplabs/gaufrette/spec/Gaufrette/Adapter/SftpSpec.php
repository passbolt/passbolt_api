<?php

namespace spec\Gaufrette\Adapter;

//hack - mock php built-in functions
require_once 'functions.php';

use PhpSpec\ObjectBehavior;

class SftpSpec extends ObjectBehavior
{
    /**
     * @param \Ssh\Sftp $sftp
     */
    function let($sftp)
    {
        $this->beConstructedWith($sftp, '/home/l3l0');
    }

    function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    function it_is_checksum_calculator()
    {
        $this->shouldHaveType('Gaufrette\Adapter\ChecksumCalculator');
    }

    /**
     * @param \Ssh\Sftp $sftp
     */
    function it_fetches_keys($sftp)
    {
        $sftp
            ->getUrl('/home/l3l0')
            ->willReturn('ssh+ssl://localhost/home/l3l0')
        ;
        $sftp
            ->listDirectory('/home/l3l0', true)
            ->willReturn(array('files' => array('/home/l3l0/filename', '/home/l3l0/filename1', '/home/l3l0/aaa/filename')))
        ;

        $this->keys()->shouldReturn(array('aaa', 'aaa/filename', 'filename', 'filename1'));
    }

    /**
     * @param \Ssh\Sftp $sftp
     */
    function it_reads_file($sftp)
    {
        $sftp
            ->getUrl('/home/l3l0')
            ->willReturn('ssh+ssl://localhost/home/l3l0')
        ;
        $sftp
            ->read('/home/l3l0/filename')
            ->shouldBeCalled()
            ->willReturn('some content')
        ;

        $this->read('filename')->shouldReturn('some content');
    }

    /**
     * @param \Ssh\Sftp $sftp
     */
    function it_writes_file($sftp)
    {
        $sftp
            ->getUrl('/home/l3l0')
            ->willReturn('ssh+ssl://localhost/home/l3l0')
        ;
        $sftp
            ->write('/home/l3l0/filename', 'some content')
            ->shouldBeCalled()
            ->willReturn(12)
        ;

        $this->write('filename', 'some content')->shouldReturn(12);
    }

    /**
     * @param \Ssh\Sftp $sftp
     */
    function it_renames_file($sftp)
    {
        $sftp
            ->getUrl('/home/l3l0')
            ->willReturn('ssh+ssl://localhost/home/l3l0')
        ;
        $sftp
            ->rename('/home/l3l0/filename', '/home/l3l0/filename1')
            ->shouldBeCalled()
            ->willReturn(true)
        ;

        $this->rename('filename', 'filename1')->shouldReturn(true);
    }

    /**
     * @param \Ssh\Sftp $sftp
     */
    function it_checks_if_file_exists($sftp)
    {
        $sftp
            ->getUrl('/home/l3l0')
            ->willReturn('ssh+ssl://localhost/home/l3l0')
        ;
        $sftp
            ->getUrl('/home/l3l0/filename')
            ->shouldBeCalled()
            ->willReturn('ssh+ssl://localhost/home/l3l0/filename')
        ;
        $sftp
            ->getUrl('/home/l3l0/filename1')
            ->shouldBeCalled()
            ->willReturn('ssh+ssl://localhost/home/l3l0/filename1')
        ;

        $this->exists('filename')->shouldReturn(true);
        $this->exists('filename1')->shouldReturn(false);
    }
}
