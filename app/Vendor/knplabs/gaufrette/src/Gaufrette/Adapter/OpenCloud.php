<?php

namespace Gaufrette\Adapter;

use Gaufrette\Adapter;
use Guzzle\Http\Exception\BadResponseException;
use OpenCloud\Common\Exceptions\DeleteError;
use OpenCloud\ObjectStore\Resource\Container;
use OpenCloud\ObjectStore\Service;
use OpenCloud\Common\Exceptions\CreateUpdateError;
use OpenCloud\ObjectStore\Exception\ObjectNotFoundException;

/**
 * OpenCloud adapter
 *
 * @package Gaufrette
 * @author  James Watson <james@sitepulse.org>
 * @author  Daniel Richter <nexyz9@gmail.com>
 */
class OpenCloud implements Adapter,
                           ChecksumCalculator
{
    /**
     * @var Service
     */
    protected $objectStore;

    /**
     * @var string
     */
    protected $containerName;

    /**
     * @var bool
     */
    protected $createContainer;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Service $objectStore
     * @param string  $containerName   The name of the container
     * @param bool    $createContainer Whether to create the container if it does not exist
     */
    public function __construct(Service $objectStore, $containerName, $createContainer = false)
    {
        $this->objectStore     = $objectStore;
        $this->containerName   = $containerName;
        $this->createContainer = $createContainer;
    }

    /**
     * Returns an initialized container
     *
     * @throws \RuntimeException
     * @return Container
     */
    protected function getContainer()
    {
        if ($this->container) {
            return $this->container;
        }

        try {
            return $this->container = $this->objectStore->getContainer($this->containerName);
        } catch (BadResponseException $e) { //OpenCloud lib does not wrap this exception
            if (!$this->createContainer) {
                throw new \RuntimeException(sprintf('Container "%s" does not exist.', $this->containerName));
            }
        }

        if (!$container = $this->objectStore->createContainer($this->containerName)) {
            throw new \RuntimeException(sprintf('Container "%s" could not be created.', $this->containerName));
        }

        return $this->container = $container;
    }

    /**
     * Reads the content of the file
     *
     * @param string $key
     *
     * @return string|boolean if cannot read content
     */
    public function read($key)
    {
        if ($object = $this->tryGetObject($key)) {
            return $object->getContent();
        }

        return false;
    }

    /**
     * Writes the given content into the file
     *
     * @param string $key
     * @param string $content
     *
     * @return integer|boolean The number of bytes that were written into the file
     */
    public function write($key, $content)
    {
        try {
            $object = $this->getContainer()->uploadObject($key, $content);
        }
        catch (CreateUpdateError $updateError) {
            return false;
        }

        return $object->getContentLength();
    }

    /**
     * Indicates whether the file exists
     *
     * @param string $key
     *
     * @return boolean
     */
    public function exists($key)
    {
        return $this->tryGetObject($key) !== false;
    }

    /**
     * Returns an array of all keys (files and directories)
     *
     * @return array
     */
    public function keys()
    {
        $objectList = $this->getContainer()->objectList();
        $keys = array ();

        while ($object = $objectList->next()) {
            $keys[] = $object->getName();
        }

        sort($keys);

        return $keys;
    }

    /**
     * Returns the last modified time
     *
     * @param string $key
     *
     * @return integer|boolean An UNIX like timestamp or false
     */
    public function mtime($key)
    {
        if ($object = $this->tryGetObject($key)) {
            return $object->getLastModified();
        }

        return false;
    }

    /**
     * Deletes the file
     *
     * @param string $key
     *
     * @return boolean
     */
    public function delete($key)
    {
        if (!$object = $this->tryGetObject($key)) {
            return false;
        }

        try {
            $object->delete();
        }
        catch (DeleteError $deleteError) {
            return false;
        }

        return true;
    }

    /**
     * Renames a file
     *
     * @param string $sourceKey
     * @param string $targetKey
     *
     * @return boolean
     */
    public function rename($sourceKey, $targetKey)
    {
        if (false !== $this->write($targetKey, $this->read($sourceKey))) {
            $this->delete($sourceKey);

            return true;
        }

        return false;
    }

    /**
     * Check if key is directory
     *
     * @param string $key
     *
     * @return boolean
     */
    public function isDirectory($key)
    {
        return false;
    }

    /**
     * Returns the checksum of the specified key
     *
     * @param string $key
     *
     * @return string
     */
    public function checksum($key)
    {
        if ($object = $this->tryGetObject($key)) {
            return $object->getETag();
        }

        return false;
    }

    /**
     * @param string $key
     *
     * @return \OpenCloud\ObjectStore\Resource\DataObject|false
     */
    protected function tryGetObject($key)
    {
        try {
            return $this->getContainer()->getObject($key);
        }
        catch (ObjectNotFoundException $objFetchError) {
            return false;
        }
    }
}
