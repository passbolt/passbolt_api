<?php

namespace Gaufrette\Adapter;

use Gaufrette\Adapter;
use Gaufrette\Util;

/**
 * In memory adapter
 *
 * Stores some files in memory for test purposes
 *
 * @package Gaufrette
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class InMemory implements Adapter
{
    protected $files = array();

    /**
     * Constructor
     *
     * @param array $files An array of files
     */
    public function __construct(array $files = array())
    {
        $this->setFiles($files);
    }

    /**
     * Defines the files
     *
     * @param array $files An array of files
     */
    public function setFiles(array $files)
    {
        $this->files = array();
        foreach ($files as $key => $file) {
            if (!is_array($file)) {
                $file = array('content' => $file);
            }

            $file = array_merge(array(
                'content'   => null,
                'mtime'     => null,
            ), $file);

            $this->setFile($key, $file['content'], $file['mtime']);
        }
    }

    /**
     * Defines a file
     *
     * @param string  $key     The key
     * @param string  $content The content
     * @param integer $mtime   The last modified time (automatically set to now if NULL)
     */
    public function setFile($key, $content = null, $mtime = null)
    {
        if (null === $mtime) {
            $mtime = time();
        }

        $this->files[$key] = array(
            'content'   => (string) $content,
            'mtime'     => (integer) $mtime
        );
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        return $this->files[$key]['content'];
    }

    /**
     * {@inheritDoc}
     */
    public function rename($sourceKey, $targetKey)
    {
        $content = $this->read($sourceKey);
        $this->delete($sourceKey);

        return (boolean) $this->write($targetKey, $content);
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content, array $metadata = null)
    {
        $this->files[$key]['content']  = $content;
        $this->files[$key]['mtime']    = time();

        return Util\Size::fromContent($content);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->files);
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        return array_keys($this->files);
    }

    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        return isset($this->files[$key]['mtime']) ? $this->files[$key]['mtime'] : false;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        unset($this->files[$key]);
        clearstatcache();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isDirectory($path)
    {
        return false;
    }
}
