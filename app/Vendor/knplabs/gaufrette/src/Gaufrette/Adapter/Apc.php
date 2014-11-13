<?php

namespace Gaufrette\Adapter;

use Gaufrette\Adapter;
use Gaufrette\Util;

/**
 * Apc adapter, a non-persistent adapter for when this sort of thing is appropriate
 *
 * @author Alexander Deruwe <alexander.deruwe@gmail.com>
 * @author Antoine Hérault <antoine.herault@gmail.com>
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class Apc implements Adapter
{
    protected $prefix;
    protected $ttl;

    /**
     * Constructor
     *
     * @throws \RuntimeException
     * @param  string            $prefix to avoid conflicts between filesystems
     * @param  int               $ttl    time to live, default is 0
     */
    public function __construct($prefix, $ttl = 0)
    {
        if (!extension_loaded('apc')) {
            throw new \RuntimeException('Unable to use Gaufrette\Adapter\Apc as the APC extension is not available.');
        }

        $this->prefix = $prefix;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        return apc_fetch($this->computePath($key));
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content, array $metadata = null)
    {
        $result = apc_store($this->computePath($key), $content, $this->ttl);

        if (!$result) {
            return false;
        }

        return Util\Size::fromContent($content);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        return apc_exists($this->computePath($key));
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        $cachedKeys = $this->getCachedKeysIterator();

        if (null === $cachedKeys) {
            return array();
        }

        $keys = array();
        foreach ($cachedKeys as $key => $value) {
            $pattern = sprintf('/^%s/', preg_quote($this->prefix, '/'));
            $keys[] = preg_replace($pattern, '', $key);
        }
        sort($keys);

        return $keys;
    }

    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        $cachedKeys = iterator_to_array($this->getCachedKeysIterator($key, APC_ITER_MTIME));

        return $cachedKeys[$this->computePath($key)]['mtime'];
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return apc_delete($this->computePath($key));
    }

    /**
     * {@inheritDoc}
     */
    public function rename($sourceKey, $targetKey)
    {
        // TODO: this probably allows for race conditions...
        $written  = $this->write($targetKey, $this->read($sourceKey));
        $deleted = $this->delete($sourceKey);

        return $written && $deleted;
    }

    /**
     * {@inheritDoc}
     */
    public function isDirectory($key)
    {
        return false;
    }

    /**
     * Computes the path for the given key
     *
     * @param  string $key
     * @return string
     */
    public function computePath($key)
    {
        return $this->prefix . $key;
    }

    /**
     * @param  string       $key    - by default ''
     * @param  integer      $format - by default APC_ITER_NONE
     * @return \APCIterator
     *
     */
    protected function getCachedKeysIterator($key = '', $format = APC_ITER_NONE)
    {
        $pattern = sprintf('/^%s/', preg_quote($this->prefix.$key, '/'));

        return new \APCIterator('user', $pattern, $format);
    }
}
