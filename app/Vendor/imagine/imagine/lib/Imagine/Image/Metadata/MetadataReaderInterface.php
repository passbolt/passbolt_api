<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Image\Metadata;

use Imagine\Exception\InvalidArgumentException;

interface MetadataReaderInterface
{
    /**
     * Reads metadata from a file.
     *
     * @param $file The path to the file where to read metadata.
     *
     * @throws InvalidArgumentException In case the file does not exist.
     *
     * @return MetadataBag
     */
    public function readFile($file);

    /**
     * Reads metadata from a binary string.
     *
     * @param $data The binary string to read.
     *
     * @return MetadataBag
     */
    public function readData($data);

    /**
     * Reads metadata from a stream.
     *
     * @param $resource The stream to read.
     *
     * @throws InvalidArgumentException In case the resource is not valid.
     *
     * @return MetadataBag
     */
    public function readStream($resource);
}
