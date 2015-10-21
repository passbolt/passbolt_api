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

abstract class AbstractMetadataReader implements MetadataReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function readFile($file)
    {
        if (stream_is_local($file)) {
            if (!is_file($file)) {
                throw new InvalidArgumentException(sprintf('File %s does not exist.', $file));
            }

            return new MetadataBag(array_merge(array('filepath' => realpath($file), 'uri' => $file), $this->extractFromFile($file)));
        }

        return new MetadataBag(array_merge(array('uri' => $file), $this->extractFromFile($file)));
    }

    /**
     * {@inheritdoc}
     */
    public function readData($data)
    {
        return new MetadataBag($this->extractFromData($data));
    }

    /**
     * {@inheritdoc}
     */
    public function readStream($resource)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('Invalid resource provided.');
        }

        return new MetadataBag(array_merge($this->getStreamMetadata($resource), $this->extractFromStream($resource)));
    }

    /**
     * Gets the URI from a stream resource
     *
     * @param resource $resource
     *
     * @return string|null The URI f ava
     */
    private function getStreamMetadata($resource)
    {
        $metadata = array();

        if (false !== $data = @stream_get_meta_data($resource)) {
            $metadata['uri'] = $data['uri'];
            if (stream_is_local($resource)) {
                $metadata['filepath'] = realpath($data['uri']);
            }
        }

        return $metadata;
    }

    /**
     * Extracts metadata from a file
     *
     * @param $file
     *
     * @return array An associative array of metadata
     */
    abstract protected function extractFromFile($file);

    /**
     * Extracts metadata from raw data
     *
     * @param $data
     *
     * @return array An associative array of metadata
     */
    abstract protected function extractFromData($data);

    /**
     * Extracts metadata from a stream
     *
     * @param $resource
     *
     * @return array An associative array of metadata
     */
    abstract protected function extractFromStream($resource);
}
