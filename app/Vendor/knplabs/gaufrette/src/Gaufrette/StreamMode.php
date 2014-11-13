<?php

namespace Gaufrette;

/**
 * Represents a stream mode
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class StreamMode
{
    private $mode;
    private $base;
    private $plus;
    private $flag;

    /**
     * Constructor
     *
     * @param string $mode A stream mode as for the use of fopen()
     */
    public function __construct($mode)
    {
        $this->mode = $mode;

        $mode = substr($mode, 0, 3);
        $rest = substr($mode, 1);

        $this->base = substr($mode, 0, 1);
        $this->plus = false !== strpos($rest, '+');
        $this->flag = trim($rest, '+');
    }

    /**
     * Returns the underlying mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Indicates whether the mode allows to read
     *
     * @return Boolean
     */
    public function allowsRead()
    {
        if ($this->plus) {
            return true;
        }

        return 'r' === $this->base;
    }

    /**
     * Indicates whether the mode allows to write
     *
     * @return Boolean
     */
    public function allowsWrite()
    {
        if ($this->plus) {
            return true;
        }

        return 'r' !== $this->base;
    }

    /**
     * Indicates whether the mode allows to open an existing file
     *
     * @return Boolean
     */
    public function allowsExistingFileOpening()
    {
        return 'x' !== $this->base;
    }

    /**
     * Indicates whether the mode allows to create a new file
     *
     * @return Boolean
     */
    public function allowsNewFileOpening()
    {
        return 'r' !== $this->base;
    }

    /**
     * Indicates whether the mode implies to delete the existing content of the
     * file when it already exists
     *
     * @return Boolean
     */
    public function impliesExistingContentDeletion()
    {
        return 'w' === $this->base;
    }

    /**
     * Indicates whether the mode implies positioning the cursor at the
     * beginning of the file
     *
     * @return Boolean
     */
    public function impliesPositioningCursorAtTheBeginning()
    {
        return 'a' !== $this->base;
    }

    /**
     * Indicates whether the mode implies positioning the cursor at the end of
     * the file
     *
     * @return Boolean
     */
    public function impliesPositioningCursorAtTheEnd()
    {
        return 'a' === $this->base;
    }

    /**
     * Indicates whether the stream is in binary mode
     *
     * @return Boolean
     */
    public function isBinary()
    {
        return 'b' === $this->flag;
    }

    /**
     * Indicates whether the stream is in text mode
     *
     * @return Boolean
     */
    public function isText()
    {
        return false === $this->isBinary();
    }
}
