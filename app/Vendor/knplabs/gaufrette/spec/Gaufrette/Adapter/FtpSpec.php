<?php

namespace spec\Gaufrette\Adapter;

//hack - mock php built-in functions
require_once 'functions.php';

use PhpSpec\ObjectBehavior;

class FtpSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('/home/l3l0', 'localhost');
    }

    function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    function it_supports_native_list_keys()
    {
        $this->shouldHaveType('Gaufrette\Adapter\ListKeysAware');
    }

    function it_checks_if_file_exists_for_absolute_path()
    {
        $this->exists('filename')->shouldReturn(true);
        $this->exists('aa/filename')->shouldReturn(false);
    }

    function it_checks_if_file_exists_for_relative_path()
    {
        $this->beConstructedWith('/home/l3l0/relative', 'localhost');

        $this->exists('filename')->shouldReturn(true);
        $this->exists('filename2')->shouldReturn(false);
        $this->exists('aa/filename')->shouldReturn(false);
        $this->exists('some/otherfilename')->shouldReturn(true);
    }

    function it_checks_if_dir_exists_for_symlink()
    {
        $this->exists('www')->shouldReturn(true);
        $this->exists('vendor')->shouldReturn(true);
        $this->exists('bbb')->shouldReturn(false);
    }

    function it_checks_if_dir_exists_with_special_and_unicode_chars_in_name()
    {
        $this->beConstructedWith('/home/l3l2', 'localhost');

        $this->exists('a b c d -> žežulička')->shouldReturn(true);
    }

    function it_reads_file()
    {
        $this->read('filename')->shouldReturn('some content');
    }

    function it_does_not_read_file()
    {
        $this->read('filename2')->shouldReturn(false);
    }

    function it_writes_file()
    {
        $this->write('filename', 'some content')->shouldReturn(12);
    }

    function it_does_not_write_file()
    {
        $this->write('filename2', 'some content')->shouldReturn(false);
    }

    function it_renames_file()
    {
        $this->rename('filename', 'filename2')->shouldReturn(true);
    }

    function it_does_not_not_rename_file_when_target_file_is_invalid()
    {
        $this->rename('filename', 'invalid')->shouldReturn(false);
    }

    function it_fetches_keys_without_directories_dots()
    {
        $this->keys()->shouldReturn(array('filename', 'filename.exe', '.htaccess', 'aaa', 'aaa/filename'));
    }

    function it_fetches_keys_with_spaces_and_unicode_chars()
    {
        $this->beConstructedWith('/home/l3l2', 'localhost');

        $this->keys()->shouldReturn(array('Žľuťoučký kůň.pdf', 'a b c d -> žežulička', 'a b c d -> žežulička/do re mi.pdf'));
    }

    function it_fetches_keys_recursive()
    {
        $this->beConstructedWith('/home/l3l3', 'localhost');

        $this->keys()->shouldReturn(array('filename', 'filename.exe', '.htaccess', 'aaa', 'www', 'aaa/filename', 'www/filename', 'www/some', 'www/some/otherfilename'));
    }

    function it_lists_keys()
    {
        $this->listKeys()->shouldReturn(array(
            'keys' => array('filename', 'filename.exe', '.htaccess', 'aaa/filename'),
            'dirs' => array('aaa')
        ));

        $this->listKeys('file')->shouldReturn(array(
            'keys' => array('filename', 'filename.exe'),
            'dirs' => array()
        ));

        $this->listKeys('name')->shouldReturn(array(
            'keys' => array(),
            'dirs' => array()
        ));

        $this->listKeys('aaa')->shouldReturn(array(
            'keys' => array('aaa/filename'),
            'dirs' => array('aaa')
        ));

        $this->listKeys('aaa/')->shouldReturn(array(
            'keys' => array('aaa/filename'),
            'dirs' => array()
        ));
    }

    function it_fetches_mtime()
    {
        $this->mtime('filename')->shouldReturn(strtotime('2010-10-10 23:10:10'));
    }

    function it_throws_exception_when_mtime_is_not_supported_by_server()
    {
        $this->shouldThrow(new \RuntimeException('Server does not support ftp_mdtm function.'))->duringMtime('invalid');
    }

    function it_deletes_file()
    {
        $this->delete('filename')->shouldReturn(true);
    }

    function it_does_not_delete_file()
    {
        $this->delete('invalid')->shouldReturn(false);
    }

    /**
     * @param \Gaufrette\Filesystem $filesystem
     */
    function it_creates_file($filesystem)
    {
        $this->createFile('filename', $filesystem)->shouldReturnAnInstanceOf('\Gaufrette\File');
    }

    /**
     * @param \Gaufrette\Filesystem $filesystem
     */
    function it_creates_file_in_not_existing_directory($filesystem)
    {
        $this->createFile('bb/cc/dd/filename', $filesystem)->shouldReturnAnInstanceOf('\Gaufrette\File');
    }

    function it_checks_if_given_key_is_directory()
    {
        $this->isDirectory('aaa')->shouldReturn(true);
        $this->isDirectory('filename')->shouldReturn(false);
    }

    function it_fetches_keys_with_hidden_files()
    {
        $this->beConstructedWith('/home/l3l1', 'localhost');

        $this->keys()->shouldReturn(array('filename', '.htaccess'));
    }

    function it_checks_if_hidden_file_exists()
    {
        $this->beConstructedWith('/home/l3l1', 'localhost');

        $this->exists('.htaccess')->shouldReturn(true);
    }

    function it_creates_base_directory_without_warning()
    {
        global $createdDirectory;
        $createdDirectory = '';

        $this->beConstructedWith('/home/l3l0/new', 'localhost', array('create' => true));

        $this->listDirectory()->shouldReturn(array('keys' => array(), 'dirs' => array()));
    }

    function it_does_not_create_base_directory_and_should_throw_exception()
    {
        global $createdDirectory;
        $createdDirectory = '';

        $this->beConstructedWith('/home/l3l0/new', 'localhost', array('create' => false));

        $this->shouldThrow(new \RuntimeException("The directory '/home/l3l0/new' does not exist."))->during('listDirectory', array());
    }

    function it_fetches_keys_for_windows()
    {
        $this->beConstructedWith('C:\Ftp', 'localhost');

        $this->keys()->shouldReturn(array('archive', 'file1.zip', 'file2.zip'));
    }
}
