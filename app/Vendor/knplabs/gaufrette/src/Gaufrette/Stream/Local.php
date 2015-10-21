<?php

namespace Gaufrette\Stream;

use Gaufrette\Stream;
use Gaufrette\StreamMode;

/**
 * Local stream
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Local implements Stream
{
    private $path;
    private $mode;
    private $fileHandle;

    /**
     * Constructor
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function open(StreamMode $mode)
    {
        $baseDirPath = dirname($this->path);
        if ($mode->allowsWrite() && !is_dir($baseDirPath)) {
            @mkdir($baseDirPath, 0755, true);
        }
        try {
            $fileHandle = @fopen($this->path, $mode->getMode());
        } catch (\Exception $e) {
            $fileHandle = false;
        }

        if (false === $fileHandle) {
            throw new \RuntimeException(sprintf('File "%s" cannot be opened', $this->path));
        }

        $this->mode = $mode;
        $this->fileHandle = $fileHandle;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function read($count)
    {
        if (! $this->fileHandle) {
            return false;
        }

        if (false === $this->mode->allowsRead()) {
            throw new \LogicException('The stream does not allow read.');
        }

        return fread($this->fileHandle, $count);
    }

    /**
     * {@inheritDoc}
     */
    public function write($data)
    {
        if (! $this->fileHandle) {
            return false;
        }

        if (false === $this->mode->allowsWrite()) {
            throw new \LogicException('The stream does not allow write.');
        }

        return fwrite($this->fileHandle, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if (! $this->fileHandle) {
            return false;
        }

        $closed = fclose($this->fileHandle);

        if ($closed) {
            $this->mode = null;
            $this->fileHandle = null;
        }

        return $closed;
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        if ($this->fileHandle) {
            return fflush($this->fileHandle);
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if ($this->fileHandle) {
            return 0 === fseek($this->fileHandle, $offset, $whence);
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function tell()
    {
        if ($this->fileHandle) {
            return ftell($this->fileHandle);
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function eof()
    {
        if ($this->fileHandle) {
            return feof($this->fileHandle);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function stat()
    {
        if ($this->fileHandle) {
            return fstat($this->fileHandle);
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function cast($castAs)
    {
        if ($this->fileHandle) {
            return $this->fileHandle;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function unlink()
    {
        if ($this->mode && $this->mode->impliesExistingContentDeletion()) {
            return @unlink($this->path);
        }

        return false;
    }
}
