<?php

namespace Gaufrette\Adapter;

use Gaufrette\File;
use Gaufrette\Adapter;
use Gaufrette\Adapter\InMemory as InMemoryAdapter;

/**
 * Cache adapter
 *
 * @package Gaufrette
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Cache implements Adapter,
                       MetadataSupporter
{
    /**
     * @var Adapter
     */
    protected $source;

    /**
     * @var Adapter
     */
    protected $cache;

    /**
     * @var integer
     */
    protected $ttl;

    /**
     * @var Adapter
     */
    protected $serializeCache;

    /**
     * Constructor
     *
     * @param Adapter $source         The source adapter that must be cached
     * @param Adapter $cache          The adapter used to cache the source
     * @param integer $ttl            Time to live of a cached file
     * @param Adapter $serializeCache The adapter used to cache serializations
     */
    public function __construct(Adapter $source, Adapter $cache, $ttl = 0, Adapter $serializeCache = null)
    {
        $this->source = $source;
        $this->cache = $cache;
        $this->ttl = $ttl;

        if (!$serializeCache) {
            $serializeCache = new InMemoryAdapter();
        }
        $this->serializeCache = $serializeCache;
    }

    /**
     * Returns the time to live of the cache
     *
     * @return integer $ttl
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Defines the time to live of the cache
     *
     * @param integer $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * {@InheritDoc}
     */
    public function read($key)
    {
        if ($this->needsReload($key)) {
            $contents = $this->source->read($key);
            $this->cache->write($key, $contents);
        } else {
            $contents = $this->cache->read($key);
        }

        return $contents;
    }

    /**
     * {@inheritDoc}
     */
    public function rename($key, $new)
    {
        return $this->source->rename($key, $new) && $this->cache->rename($key, $new);
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content, array $metadata = null)
    {
        $bytesSource = $this->source->write($key, $content);

        if (false === $bytesSource) {
            return false;
        }

        $bytesCache = $this->cache->write($key, $content);

        if ($bytesSource !== $bytesCache) {
            return false;
        }

        return $bytesSource;
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        if ($this->needsReload($key)) {
            return $this->source->exists($key);
        }
        return $this->cache->exists($key);
    }

    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        return $this->source->mtime($key);
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        $cacheFile = 'keys.cache';
        if ($this->needsRebuild($cacheFile)) {
            $keys = $this->source->keys();
            sort($keys);
            $this->serializeCache->write($cacheFile, serialize($keys));
        } else {
            $keys = unserialize($this->serializeCache->read($cacheFile));
        }

        return $keys;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return $this->source->delete($key) && $this->cache->delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function isDirectory($key)
    {
        return $this->source->isDirectory($key);
    }

    /**
     * {@inheritDoc}
     */
    public function setMetadata($key, $metadata)
    {
        if ($this->source instanceof MetadataSupporter) {
            $this->source->setMetadata($key, $metadata);
        }

        if ($this->cache instanceof MetadataSupporter) {
            $this->cache->setMetadata($key, $metadata);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata($key)
    {
        if ($this->source instanceof MetadataSupporter) {
            return $this->source->getMetadata($key);
        }

        return false;
    }

    /**
     * Indicates whether the cache for the specified key needs to be reloaded
     *
     * @param string $key
     */
    public function needsReload($key)
    {
        $needsReload = true;

        if ($this->cache->exists($key)) {
            try {
                $dateCache = $this->cache->mtime($key);
                $needsReload = false;

                if (time() - $this->ttl >= $dateCache) {
                    $dateSource = $this->source->mtime($key);
                    $needsReload = $dateCache < $dateSource;
                }
            } catch (\RuntimeException $e) { }
        }

        return $needsReload;
    }

    /**
     * Indicates whether the serialized cache file needs to be rebuild
     *
     * @param string $cacheFile
     */
    public function needsRebuild($cacheFile)
    {
        $needsRebuild = true;

        if ($this->serializeCache->exists($cacheFile)) {
            try {
                $needsRebuild = time() - $this->ttl >= $this->serializeCache->mtime($cacheFile);
            } catch (\RuntimeException $e) { }
        }

        return $needsRebuild;
    }
}
