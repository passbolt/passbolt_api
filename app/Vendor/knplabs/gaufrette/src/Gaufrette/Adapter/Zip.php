<?php

namespace Gaufrette\Adapter;

use ZipArchive;
use Gaufrette\Adapter;
use Gaufrette\Util;
use Gaufrette\Exception;

/**
 * ZIP Archive adapter
 *
 * @author Boris Guéry <guery.b@gmail.com>
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Zip implements Adapter
{
    /**
     * @var string The zip archive full path
     */
    protected $zipFile;

    /**
     * @var ZipArchive
     */
    protected $zipArchive;

    public function __construct($zipFile)
    {
        if (!extension_loaded('zip')) {
            throw new \RuntimeException(sprintf(
                'Unable to use %s as the ZIP extension is not available.',
                __CLASS__
            ));
        }

        $this->zipFile = $zipFile;
        $this->reinitZipArchive();
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        if (false === ($content = $this->zipArchive->getFromName($key, 0))) {
            return false;
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content)
    {
        if (!$this->zipArchive->addFromString($key, $content)) {
            return false;
        }

        if (!$this->save()) {
            return false;
        }

        return Util\Size::fromContent($content);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        return (boolean) $this->getStat($key);
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        $keys = array();

        for ($i = 0; $i < $this->zipArchive->numFiles; ++$i) {
            $keys[$i] = $this->zipArchive->getNameIndex($i);
        }

        return $keys;
    }

    /**
     * @todo implement
     *
     * {@inheritDoc}
     */
    public function isDirectory($key)
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        $stat = $this->getStat($key);

        return isset($stat['mtime']) ? $stat['mtime'] : false;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        if (!$this->zipArchive->deleteName($key)) {
            return false;
        }

        return $this->save();
    }

    /**
     * {@inheritDoc}
     */
    public function rename($sourceKey, $targetKey)
    {
        if (!$this->zipArchive->renameName($sourceKey, $targetKey)) {
            return false;
        }

        return $this->save();
    }

    /**
     * Returns the stat of a file in the zip archive
     *  (name, index, crc, mtime, compression size, compression method, filesize)
     *
     * @param $key
     * @return array|bool
     */
    public function getStat($key)
    {
        $stat = $this->zipArchive->statName($key);
        if (false === $stat) {
            return array();
        }

        return $stat;
    }

    public function __destruct()
    {
        if ($this->zipArchive) {
            try {
                $this->zipArchive->close();
            } catch (\Exception $e) {

            }
            unset($this->zipArchive);
        }
    }

    protected function reinitZipArchive()
    {
        $this->zipArchive = new ZipArchive();

        if (true !== ($resultCode = $this->zipArchive->open($this->zipFile, ZipArchive::CREATE))) {
            switch ($resultCode) {
            case ZipArchive::ER_EXISTS:
                $errMsg = 'File already exists.';
                break;
            case ZipArchive::ER_INCONS:
                $errMsg = 'Zip archive inconsistent.';
                break;
            case ZipArchive::ER_INVAL:
                $errMsg = 'Invalid argument.';
                break;
            case ZipArchive::ER_MEMORY:
                $errMsg = 'Malloc failure.';
                break;
            case ZipArchive::ER_NOENT:
                $errMsg = 'Invalid argument.';
                break;
            case ZipArchive::ER_NOZIP:
                $errMsg = 'Not a zip archive.';
                break;
            case ZipArchive::ER_OPEN:
                $errMsg = 'Can\'t open file.';
                break;
            case ZipArchive::ER_READ:
                $errMsg = 'Read error.';
                break;
            case ZipArchive::ER_SEEK;
                $errMsg = 'Seek error.';
                break;
            default:
                $errMsg = 'Unknown error.';
                break;
            }

            throw new \RuntimeException(sprintf('%s', $errMsg));
        }

        return $this;
    }

    /**
     * Saves archive modifications and updates current ZipArchive instance
     *
     * @throws \RuntimeException If file could not be saved
     */
    protected function save()
    {
        // Close to save modification
        if (!$this->zipArchive->close()) {
            return false;
        }

        // Re-initialize to get updated version
        $this->reinitZipArchive();

        return true;
    }
}
