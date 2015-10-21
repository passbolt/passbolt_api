<?php

namespace Gaufrette\Functional\Adapter;

use Gaufrette\Filesystem;
use Gaufrette\Adapter\Cache;
use Gaufrette\Adapter\InMemory;

class CacheTest extends FunctionalTestCase
{
    public function setUp()
    {
        $this->filesystem = new Filesystem(new Cache(new InMemory(), new InMemory()));
    }

    /**
     * @test
     */
    public function shouldNeedReloadAfterSourceChanged()
    {
        $source = new InMemory();
        $cache = new InMemory();
        $cachedFs = new Cache($source, $cache);

        // The source has been updated after the cache has been created.
        $mtime = time();
        $source->setFile('foo', 'baz', $mtime - 10);
        $cache->setFile('foo', 'bar', $mtime - 20);

        $this->assertTrue($cachedFs->needsReload('foo'));
        $this->assertEquals('baz', $cachedFs->read('foo'));
    }
}
