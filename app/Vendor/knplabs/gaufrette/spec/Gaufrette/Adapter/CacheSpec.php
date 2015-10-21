<?php

namespace spec\Gaufrette\Adapter;

use PhpSpec\ObjectBehavior;

class CacheSpec extends ObjectBehavior
{
    /**
     * @param \Gaufrette\Adapter $source
     * @param \Gaufrette\Adapter $cache
     */
    function let($source, $cache)
    {
        $this->beConstructedWith($source, $cache);
    }

    function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    function it_supports_metadata()
    {
        $this->shouldHaveType('Gaufrette\Adapter\MetadataSupporter');
    }

    /**
     * @param \spec\Gaufrette\Adapter\CacheTestExtendedAdapter $extendedSource
     * @param \spec\Gaufrette\Adapter\CacheTestExtendedAdapter $extendedCache
     */
    function it_handles_metadata_when_cached_adapters_supports_metadata($extendedSource, $extendedCache)
    {
        $extendedSource->setMetadata('filename', array('metadata'))->shouldBeCalled();
        $extendedCache->setMetadata('filename', array('metadata'))->shouldBeCalled();
        $extendedSource->getMetadata('filename')->shouldBeCalled()->willReturn(array('someMetadata'));
        $this->beConstructedWith($extendedSource, $extendedCache);

        $this->setMetadata('filename', array('metadata'));
        $this->getMetadata('filename')->shouldReturn(array('someMetadata'));
    }

    /**
     * @param \Gaufrette\Adapter $source
     */
    function it_delegates_is_directory_check_to_source($source)
    {
        $source->isDirectory('filename')->shouldBeCalled()->willReturn(true);

        $this->isDirectory('filename')->shouldReturn(true);
    }

    /**
     * @param \Gaufrette\Adapter $source
     * @param \Gaufrette\Adapter $cache
     */
    function it_reads_from_cache_adapter($source, $cache)
    {
        $source->read('filename')->shouldNotBeCalled();
        $cache->read('filename')->shouldBeCalled()->willReturn('some content');
        $source->mtime('filename')->willReturn(strtotime('2010-10-11'));
        $cache->mtime('filename')->willReturn(strtotime('2010-10-12'));
        $cache->exists('filename')->willReturn(true);

        $this->read('filename')->shouldReturn('some content');
    }

    /**
     * @param \Gaufrette\Adapter $source
     * @param \Gaufrette\Adapter $cache
     */
    function it_update_cache_adapter_when_source_file_is_modified($source, $cache)
    {
        $source->read('filename')->shouldBeCalled()->willReturn('some other content');
        $cache->read('filename')->shouldNotBeCalled();
        $cache->write('filename', 'some other content')->shouldBeCalled();
        $source->mtime('filename')->willReturn(strtotime('2010-10-11'));
        $cache->mtime('filename')->willReturn(strtotime('2010-10-10'));
        $cache->exists('filename')->willReturn(true);

        $this->read('filename')->shouldReturn('some other content');
    }

    /**
     * @param \Gaufrette\Adapter $source
     * @param \Gaufrette\Adapter $cache
     */
    function it_rename_file_in_source_and_cache($source, $cache)
    {
        $source->rename('filename', 'filename2')->shouldBeCalled()->willReturn(true);
        $cache->rename('filename', 'filename2')->shouldBeCalled()->willReturn(true);

        $this->rename('filename', 'filename2')->shouldReturn(true);
    }

    /**
     * @param \Gaufrette\Adapter $source
     * @param \Gaufrette\Adapter $cache
     */
    function it_writes_file_to_source_and_cache($source, $cache)
    {
        $source->write('filename', 'some content')->shouldBeCalled()->willReturn(12);
        $cache->write('filename', 'some content')->shouldBeCalled()->willReturn(12);

        $this->write('filename', 'some content')->shouldReturn(12);
    }

    /**
     * @param \Gaufrette\Adapter $source
     * @param \Gaufrette\Adapter $cache
     */
    function it_deletes_file_from_source_and_cache($source, $cache)
    {
        $source->delete('filename')->shouldBeCalled()->willReturn(true);
        $cache->delete('filename')->shouldBeCalled()->willReturn(true);

        $this->delete('filename')->shouldReturn(true);
    }

    /**
     * @param \Gaufrette\Adapter $source
     */
    function it_check_if_exists_in_source($source)
    {
        $source->exists('filename')->shouldBeCalled()->willReturn(true);

        $this->exists('filename')->shouldReturn(true);
    }

    /**
     * @param \Gaufrette\Adapter $source
     */
    function it_get_mtime_from_source($source)
    {
        $source->mtime('filename')->shouldBeCalled()->willReturn(1234);

        $this->mtime('filename')->shouldReturn(1234);
    }

    /**
     * @param \Gaufrette\Adapter $source
     */
    function it_get_keys_from_source($source)
    {
        $source->keys()->willReturn(array('filename2', 'filename1', 'filename'));

        $this->keys()->shouldReturn(array('filename', 'filename1', 'filename2'));
    }
}

interface CacheTestExtendedAdapter extends \Gaufrette\Adapter,
                                           \Gaufrette\Adapter\ChecksumCalculator,
                                           \Gaufrette\Adapter\MetadataSupporter
{

}
