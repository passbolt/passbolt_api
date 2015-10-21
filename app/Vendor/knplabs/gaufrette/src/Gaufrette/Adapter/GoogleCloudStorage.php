<?php

namespace Gaufrette\Adapter;

use Gaufrette\Adapter;

/**
 * Google Cloud Storage adapter using the Google APIs Client Library for PHP
 *
 * @package Gaufrette
 * @author  Patrik Karisch <patrik@karisch.guru>
 */
class GoogleCloudStorage implements Adapter,
                                    MetadataSupporter,
                                    ListKeysAware
{
    protected $service;
    protected $bucket;
    protected $options;
    protected $bucketExists;
    protected $metadata = array();
    protected $detectContentType;

    /**
     * @param \Google_Service_Storage $service           The storage service class with authenticated
     *                                                   client and full access scope
     * @param string                  $bucket            The bucket name
     * @param array                   $options           Options can be directory and acl
     * @param bool                    $detectContentType Whether to detect the content type or not
     */
    public function __construct(
        \Google_Service_Storage $service,
        $bucket,
        array $options = array(),
        $detectContentType = false
    ) {
        $this->service = $service;
        $this->bucket = $bucket;
        $this->options = array_replace(
            array(
                'directory' => '',
                'acl' => 'private',
            ),
            $options
        );

        $this->detectContentType = $detectContentType;
    }

    /**
     * @return array The actual options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options The new options
     */
    public function setOptions($options)
    {
        $this->options = array_replace($this->options, $options);
    }

    /**
     * @return string The current bucket name
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * Sets a new bucket name.
     *
     * @param string $bucket The new bucket name
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * {@inheritdoc}
     */
    public function read($key)
    {
        $this->ensureBucketExists();
        $path = $this->computePath($key);

        $object = $this->getObjectData($path);
        if ($object === false) {
            return false;
        }

        $request = new \Google_Http_Request($object->getMediaLink());
        $this->service->getClient()->getAuth()->sign($request);

        $response = $this->service->getClient()->getIo()->executeRequest($request);

        if ($response[2] == 200) {
            $this->setMetadata($key, $object->getMetadata());

            return $response[0];
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function write($key, $content)
    {
        $this->ensureBucketExists();
        $path = $this->computePath($key);

        $metadata = $this->getMetadata($key);
        $options = array(
            'uploadType' => 'multipart',
            'data' => $content
        );

        /**
         * If the ContentType was not already set in the metadata, then we autodetect
         * it to prevent everything being served up as application/octet-stream.
         */
        if (!isset($metadata['ContentType']) && $this->detectContentType) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $options['mimeType'] = $finfo->buffer($content);
            unset($metadata['ContentType']);
        } elseif (isset($metadata['ContentType'])) {
            $options['mimeType'] = $metadata['ContentType'];
            unset($metadata['ContentType']);
        }

        $object = new \Google_Service_Storage_StorageObject();
        $object->name = $path;
        $object->setMetadata($metadata);

        try {
            $object = $this->service->objects->insert($this->bucket, $object, $options);

            if ($this->options['acl'] == 'public') {
                $acl = new \Google_Service_Storage_ObjectAccessControl();
                $acl->setEntity("allUsers");
                $acl->setRole("READER");

                $this->service->objectAccessControls->insert($this->bucket, $path, $acl);
            }

            return $object->getSize();
        } catch (\Google_Service_Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        $this->ensureBucketExists();
        $path = $this->computePath($key);

        try {
            $this->service->objects->get($this->bucket, $path);
        } catch (\Google_Service_Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return $this->listKeys();
    }

    /**
     * {@inheritdoc}
     */
    public function mtime($key)
    {
        $this->ensureBucketExists();
        $path = $this->computePath($key);

        $object = $this->getObjectData($path);

        return $object ? strtotime($object->getUpdated()) : false;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        $this->ensureBucketExists();
        $path = $this->computePath($key);

        try {
            $this->service->objects->delete($this->bucket, $path);
        } catch (\Google_Service_Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rename($sourceKey, $targetKey)
    {
        $this->ensureBucketExists();
        $sourcePath = $this->computePath($sourceKey);
        $targetPath = $this->computePath($targetKey);

        $object = $this->getObjectData($sourcePath);
        if ($object === false) {
            return false;
        }

        try {
            $this->service->objects->copy($this->bucket, $sourcePath, $this->bucket, $targetPath, $object);
            $this->delete($sourcePath);
        } catch (\Google_Service_Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isDirectory($key)
    {
        if ($this->exists($key . '/')) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function listKeys($prefix = '')
    {
        $this->ensureBucketExists();

        $options = array();
        if ((string)$prefix != '') {
            $options['prefix'] = $this->computePath($prefix);
        } elseif (!empty($this->options['directory'])) {
            $options['prefix'] = $this->options['directory'];
        }

        $list = $this->service->objects->listObjects($this->bucket, $options);
        $keys = array();

        // FIXME: Temporary workaround for google/google-api-php-client#375
        $reflectionClass = new \ReflectionClass('Google_Service_Storage_Objects');
        $reflectionProperty = $reflectionClass->getProperty('collection_key');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($list, 'items');

        /** @var \Google_Service_Storage_StorageObject $object */
        foreach ($list as $object) {
            $keys[] = $object->name;
        }

        sort($keys);

        return $keys;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadata($key, $content)
    {
        $path = $this->computePath($key);

        $this->metadata[$path] = $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($key)
    {
        $path = $this->computePath($key);

        return isset($this->metadata[$path]) ? $this->metadata[$path] : array();
    }

    /**
     * Ensures the specified bucket exists.
     *
     * @throws \RuntimeException if the bucket does not exists
     */
    protected function ensureBucketExists()
    {
        if ($this->bucketExists) {
            return;
        }

        try {
            $this->service->buckets->get($this->bucket);
            $this->bucketExists = true;

            return;
        } catch (\Google_Service_Exception $e) {
            $this->bucketExists = false;

            throw new \RuntimeException(
                sprintf(
                    'The configured bucket "%s" does not exist.',
                    $this->bucket
                )
            );
        }
    }

    protected function computePath($key)
    {
        if (empty($this->options['directory'])) {
            return $key;
        }

        return sprintf('%s/%s', $this->options['directory'], $key);
    }

    /**
     * @param string $path
     * @param array  $options
     *
     * @return bool|\Google_Service_Storage_StorageObject
     */
    private function getObjectData($path, $options = array())
    {
        try {
            return $this->service->objects->get($this->bucket, $path, $options);
        } catch (\Google_Service_Exception $e) {
            return false;
        }
    }
}
