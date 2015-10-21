<?php

namespace Gaufrette\Adapter;

/**
 * Interface for the stream creation class
 *
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
interface StreamFactory
{
    /**
     * Creates a new stream instance of the specified file
     *
     * @param string $key
     *
     * @return Gaufrette\Stream
     */
    public function createStream($key);
}
